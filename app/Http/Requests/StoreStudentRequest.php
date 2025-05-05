<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
            'name'          => 'sometimes|required|regex:/^[a-zA-Zà-úÀ-Ú\s\-_]+$/|max:60', 
            'email'         => 'sometimes|required|unique:students|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'gender'        => 'sometimes|max:1|in:M,F',
            'data_of_birth' => 'sometimes|required'
        ];
    }

    public function messages()
    {
        return [
            'name.required'          => 'The name is required.',
            'name.regex'             => 'Please, just letters..',
            'name.max'               => 'The number of characters has been exceeded in the name.',
            'email.required'         => 'The email is required.',
            'email.unique'           => 'The email must be unique.',
            'email.regex'            => 'Enter a valid email.',
            'gender.max'             => 'Just one letter, M or F',
            'gender.in'              => 'Just M or F',
            'data_of_birth.required' => 'The data of birth is required.'
        ];
    }
}
