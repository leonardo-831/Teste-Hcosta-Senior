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
            'status' => 'required|exists:status,id',
            'assigneeId' => 'nullable|integer|exists:users,id',
        ];
    }
}
