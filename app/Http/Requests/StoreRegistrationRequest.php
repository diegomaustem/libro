<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest
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
            'course_id'  => 'required|integer', 
            'student_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'course_id.required'  => 'Course ID is required.',
            'course_id.integer'   => 'Please only integers.',
            'student_id.required' => 'Student ID is required.',
            'student_id.integer'  => 'Please only integers.',
        ];
    }
}
