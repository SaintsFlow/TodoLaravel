<?php

namespace App\Http\Requests;

use App\Enum\TodoPriority;
use App\Enum\TodoStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTodoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Авторизация происходит через middleware
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'priority' => ['nullable', Rule::in(array_column(TodoPriority::cases(), 'value'))],
            'status' => ['nullable', Rule::in(array_column(TodoStatus::cases(), 'value'))],
            'assigned_to_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'is_private' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Название задачи обязательно',
            'title.max' => 'Название задачи не должно превышать 255 символов',
            'description.max' => 'Описание не должно превышать 5000 символов',
            'color.regex' => 'Цвет должен быть в формате hex (#FFFFFF)',
            'priority.in' => 'Приоритет должен быть одним из: ' . implode(', ', array_column(TodoPriority::cases(), 'value')),
            'status.in' => 'Статус должен быть одним из: ' . implode(', ', array_column(TodoStatus::cases(), 'value')),
            'assigned_to_id.exists' => 'Пользователь для назначения не найден'
        ];
    }
}
