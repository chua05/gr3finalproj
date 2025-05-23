<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
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
            'Title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('books')->ignore($this->book->id),
            ],
            'Author' => 'required|string|max:255',
            'Category' => 'required|integer|min:1',
        ];
    }
}
