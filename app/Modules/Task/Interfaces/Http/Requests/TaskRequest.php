<?php

namespace App\Modules\Task\Interfaces\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'statusId' => 'required|exists:status,id',
            'assigneeId' => 'nullable|integer|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser um texto.',
            'name.max' => 'O nome não pode ter mais que 255 caracteres.',

            'description.string' => 'A descrição deve ser um texto.',

            'statusId.required' => 'O status é obrigatório.',
            'statusId.exists' => 'O status selecionado é inválido.',

            'assigneeId.integer' => 'O ID do responsável deve ser um número.',
            'assigneeId.exists' => 'O responsável selecionado é inválido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'description' => 'descrição',
            'statusId' => 'status',
            'assigneeId' => 'responsável',
        ];
    }
}
