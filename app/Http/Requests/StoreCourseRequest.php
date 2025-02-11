<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
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
            'title'       => 'required|max:115|regex:/^[^0-9]*$/',
            'description' => 'max:255' 
        ];
    }

    public function messages()
    {
        return [
            'title.required'  => 'Title is required.',
            'title.max'       => 'The number of characters has been exceeded in the title.',
            'title.regex'     => 'Just letters please.',
            'description.max' => 'The number of characters has been exceeded in the description.'
        ];
    }
}
