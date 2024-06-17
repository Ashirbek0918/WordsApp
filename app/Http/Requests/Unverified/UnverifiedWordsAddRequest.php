<?php

namespace App\Http\Requests\Unverified;

use Illuminate\Foundation\Http\FormRequest;

class UnverifiedWordsAddRequest extends FormRequest
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
            'word' =>'required|string|max:255|unique:unverifieds,word',
            'user_id' =>'required|exists:users,id',
        ];
    }
}
