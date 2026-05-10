<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class TelegramService
{
    private string $botToken;
    private string $chatId;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token') ?? '';
        $this->chatId = config('services.telegram.chat_id') ?? '';
    }

    public function sendProductToChannel(Product $product): bool
    {
        if (empty($this->botToken) || empty($this->chatId)) {
            Log::warning('Telegram bot credentials are not configured.');
            return false;
        }

        try {
            // Build the caption
            $title = $product->title;
            // Decode potential HTML entities and strip tags
            $descriptionText = strip_tags((string) $product->description);
            $excerpt = Str::limit($descriptionText, 250);
            
            // Format HTML caption correctly
            $caption = "<b>{$title}</b>\n\n{$excerpt}";

            // Build inline keyboard
            // Fallback demo url to the product page if not set
            $demoUrl = $product->demo_url ?: route('products.show', $product);
            $downloadUrl = route('products.show', $product);

            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => 'Live Demo ↗', 'url' => $demoUrl],
                    ],
                    [
                        ['text' => 'Download ↗', 'url' => $downloadUrl],
                    ]
                ]
            ];

            $url = "https://api.telegram.org/bot{$this->botToken}/sendPhoto";

            $photoPath = null;
            if ($product->preview_path) {
                $photoPath = storage_path('app/public/' . ltrim($product->preview_path, '/'));
            } else {
                $media = $product->getFirstMedia('preview');
                if ($media) {
                    $photoPath = $media->getPath();
                }
            }

            if ($photoPath && file_exists($photoPath)) {
                $response = Http::withoutVerifying()->attach(
                    'photo',
                    file_get_contents($photoPath),
                    basename($photoPath)
                )->post($url, [
                    'chat_id' => $this->chatId,
                    'caption' => $caption,
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode($keyboard),
                ]);
            } else {
                // If no photo found, send as standard text message
                $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";
                $response = Http::withoutVerifying()->post($url, [
                    'chat_id' => $this->chatId,
                    'text' => $caption,
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode($keyboard),
                ]);
            }

            if (!$response->successful()) {
                Log::error('Failed to send Telegram message', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (Throwable $e) {
            Log::error('Exception sending Telegram message: ' . $e->getMessage());
            return false;
        }
    }
}
