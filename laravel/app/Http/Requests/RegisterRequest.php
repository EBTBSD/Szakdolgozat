<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'firstname' => 'required|string|max:30',
            'lastname'  => 'required|string|max:30',
            'email' => 'required|email|unique:users,email',
            'password' => ['required','string','confirmed','regex:/^\S*/u',Password::min(8)->mixedCase()->numbers()]
        ];
    }

    public function messages()
    {
        return [
            'firstname.required' => 'Keresztnév megadása kötelező!',
            'firstname.max' => 'Keresztnév maximum 30 hosszúságú lehet!',

            'lastname.required' => 'Vezetéknév megadása kötelező!',
            'lastname.max' => 'Vezetéknév maximum 30 hosszúságú lehet!',

            'email.required' => 'e-mail cím megadása kötelező!',
            'email.unique' => 'Ezzel az e-mail cimmel regisztráltak!',
            'email.email' => 'Nem megfelelő e-mail cím formátum!',

            'password.required' => 'A jelszó megadása kötelező!',
            'password.min' => 'Jelszó minimum 8 karakter hosszúságnak kell lennie!',
            'password.confirmed' => 'Jelszavak nem egyeznek!',
            'password.regex' => 'Jelszó nem tartalmazhat szóközt!',
            'password.mixed' => 'Jelszónak tartalmaznia kell kis és nagy betűt!',
            'password.numbers' => 'Jelszónak tartalmaznia kell számot!',
            //'password.symbols' => 'Jelszónak tartalmaznia kell speciális karaktert!'
        ];
    }
}
