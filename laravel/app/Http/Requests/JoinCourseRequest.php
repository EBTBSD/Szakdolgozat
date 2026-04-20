<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JoinCourseRequest extends FormRequest
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
            'invite_code' => 'required|string|min:6'
        ];
    }

    public function messages(): array
    {
        return [
            'invite_code.required' => 'A meghívókód megadása kötelező!',
            'invite_code.string' => 'Érvénytelen kód formátum!',
            'invite_code.min' => 'A meghívókódnak legalább 6 karakterből kell állnia!'
        ];
    }
}
