<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
            'course_name' => 'required|string|min:5|max:255',
            'course_type' => 'required|string|max:50',
            'course_img'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ];
    }

    public function messages(): array
    {
        return [
            'course_name.required' => 'A kurzus nevének megadása kötelező!',
            'course_name.min'      => 'A kurzus neve legalább 5 karakter hosszú kell legyen!',
            'course_type.required' => 'Kérlek válaszd ki a kurzus típusát!',
            'course_img.image'     => 'A feltöltött fájl csak kép lehet (jpeg, png, jpg, gif)!',
            'course_img.max'       => 'A kép mérete nem haladhatja meg a 2MB-ot!',
        ];
    }
}
