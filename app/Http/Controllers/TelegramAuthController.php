<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TelegramAuthController extends Controller
{
    /**
     * Handle the Telegram Login Widget callback.
     *
     * Telegram sends all user data as GET parameters to data-auth-url.
     * We verify authenticity via HMAC-SHA256 before trusting any data.
     */
    public function callback(Request $request): RedirectResponse
    {
        $data = $request->all();

        if (!$this->validateTelegramHash($data)) {
            Log::warning('Telegram auth: invalid hash attempt.', [
                'ip'   => $request->ip(),
                'data' => array_keys($data),
            ]);

            return redirect()->route('login')
                ->withErrors(['telegram' => 'Telegram authentication failed. Please try again.']);
        }

        // Reject stale sessions (older than 24 hours)
        if ((time() - (int) ($data['auth_date'] ?? 0)) > 86400) {
            return redirect()->route('login')
                ->withErrors(['telegram' => 'Telegram session expired. Please try again.']);
        }

        $user = User::firstOrCreate(
            ['telegram_id' => (int) $data['id']],
            [
                'name'               => $this->buildName($data),
                'telegram_username'  => $data['username'] ?? null,
                'email'              => null,
                'password'           => null,
            ]
        );

        // Sync username in case it changed
        if (($data['username'] ?? null) !== $user->telegram_username) {
            $user->update(['telegram_username' => $data['username'] ?? null]);
        }

        Auth::login($user, remember: true);

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Validate the hash sent by Telegram Login Widget.
     *
     * Algorithm (official):
     * 1. Build a sorted key=value string from all fields except 'hash'
     * 2. secret_key = SHA256(bot_token)  ← raw bytes, not hex
     * 3. calculated_hash = HMAC-SHA256(data_check_string, secret_key)
     * 4. Compare calculated_hash with the received hash
     */
    private function validateTelegramHash(array $data): bool
    {
        $botToken = config('services.telegram.bot_token');

        if (empty($botToken) || !isset($data['hash'])) {
            return false;
        }

        $receivedHash = $data['hash'];
        unset($data['hash']);

        ksort($data);

        $dataCheckString = implode(
            "\n",
            array_map(
                static fn (string $key, mixed $value): string => "{$key}={$value}",
                array_keys($data),
                array_values($data)
            )
        );

        // Secret key is SHA256 of bot token (raw binary output)
        $secretKey       = hash('sha256', $botToken, true);
        $calculatedHash  = hash_hmac('sha256', $dataCheckString, $secretKey);

        return hash_equals($calculatedHash, $receivedHash);
    }

    /**
     * Build a display name from Telegram first_name + last_name.
     */
    private function buildName(array $data): string
    {
        return trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''))
            ?: ($data['username'] ?? 'Telegram User');
    }
}
