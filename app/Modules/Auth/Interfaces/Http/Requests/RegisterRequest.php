<?php

namespace App\Modules\Auth\Interfaces\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser um texto.',
            'name.max' => 'O nome não pode ter mais que 255 caracteres.',

            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O campo e-mail deve conter um endereço de e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso.',

            'password.required' => 'O campo senha é obrigatório.',
            'password.string' => 'O campo senha deve ser um texto.',
            'password.min' => 'A senha deve ter no mínimo 6 caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'email' => 'e-mail',
            'password' => 'senha',
        ];
    }
}
