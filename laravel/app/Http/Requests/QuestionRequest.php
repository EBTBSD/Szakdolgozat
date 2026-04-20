<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
            'question_text' => 'required|string|max:1000',
            'question_type' => 'required|string|max:50',
            'question_points' => 'required|integer|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            'question_text.required' => 'A kérdés szövegének megadása kötelező!',
            'question_type.required' => 'A kérdés típusát kötelező megadni!',
            'question_points.min' => 'A kérdésnek legalább 1 pontot kell érnie!'
        ];
    }
}
