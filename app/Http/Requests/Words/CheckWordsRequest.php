<?php

namespace App\Http\Requests\Words;

use Illuminate\Foundation\Http\FormRequest;

class CheckWordsRequest extends FormRequest
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
            'unverified_id' =>'required|exists:unverifieds,id',
            'type' =>'required|in:true,false',
        ];
    }
}
