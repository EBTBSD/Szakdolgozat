<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class newCourseRequest extends FormRequest
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
            "course_name" => "required|string|max:255",
            "course_type" => "required|string|max:255",
            "course_img_path" => "nullable|string|max:255",
            "creator_username" => "string|max:255",
            "course_users" => "nullable|string|max:255"
        ];
    }
}
