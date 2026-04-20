<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnswerRequest extends FormRequest
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
            'answer_text' => 'required|string|max:1000',
            'is_correct' => 'required|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'answer_text.required' => 'A válasz szövegét kötelező megadni!',
            'is_correct.boolean' => 'A helyesség csak igaz vagy hamis érték lehet!'
        ];
    }
}
