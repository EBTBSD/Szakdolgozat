<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentRequest extends FormRequest
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
            'assignment_name' => 'required|string|max:255',
            'assignment_type' => 'required|string|max:100',
            'assignment_max_point' => 'required|integer|min:1',
            'assignment_deadline' => 'required|date',
            'assignment_accessible' => 'required|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'assignment_name.required' => 'A feladat nevének megadása kötelező!',
            'assignment_max_point.min' => 'A maximális pontszámnak legalább 1-nek kell lennie!',
            'assignment_deadline.date' => 'A határidőnek érvényes dátumnak kell lennie!',
            'assignment_accessible.boolean' => 'A láthatóság csak igaz vagy hamis lehet!'
        ];
    }
}
