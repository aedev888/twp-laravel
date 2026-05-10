<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'      => 'Please enter your name.',
            'email.required'     => 'Please enter your email.',
            'email.unique'       => 'This email is already registered. Try logging in.',
            'password.min'       => 'Password must be at least 8 characters.',
            'password.letters'   => 'Password must contain at least one letter.',
            'password.numbers'   => 'Password must contain at least one number.',
            'password.confirmed' => 'Passwords do not match.',
        ];
    }
}
