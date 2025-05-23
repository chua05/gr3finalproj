<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email', // Ensure unique email
            'password' => [
                'required',
                'confirmed', // password confirmation matches
                Password::min(8) // minimum password length of 8 characters
                    ->letters() // Must contain letters
                    ->mixedCase() // Must contain both uppercase and lowercase
                    ->numbers() // Must contain numbers
                    ->symbols() // Must contain symbols
            ],
        'role' => 'sometimes|in:admin,user', // Optional 'role', must be 'admin' or 'user'
        ];
    }

    /**
     * Get the custom validation messages for the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}

