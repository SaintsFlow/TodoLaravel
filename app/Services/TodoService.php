<?php

namespace App\Services;

use App\Contracts\TodoRepositoryInterface;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TodoService
{
    public function __construct(
        private TodoRepositoryInterface $todoRepository
    )
    {
    }

    public function getAllTodos(User $user, array $filters = []): LengthAwarePaginator
    {
        return $this->todoRepository->getAllForUser($user, $filters);
    }

    public function createTodo(array $data, User $user): Todo
    {
        $data['author_id'] = $user->id;

        // Если assigned_to_id не указан, назначаем автору
        if (!isset($data['assigned_to_id'])) {
            $data['assigned_to_id'] = $user->id;
        }

        return $this->todoRepository->create($data);
    }

    public function updateTodo(int $id, array $data, User $user): Todo
    {
        $todo = $this->getTodoById($id, $user);

        if (!$this->canUserModifyTodo($user, $todo)) {
            throw new AuthorizationException('You can only modify your own todos');
        }

        return $this->todoRepository->update($todo, $data);
    }

    public function getTodoById(int $id, User $user): Todo
    {
        $todo = $this->todoRepository->findById($id);

        if (!$todo) {
            abort(404, 'Todo not found');
        }

        if (!$this->canUserAccessTodo($user, $todo)) {
            throw new AuthorizationException('Access denied');
        }

        return $todo;
    }

    private function canUserAccessTodo(User $user, Todo $todo): bool
    {
        return $todo->author_id === $user->id
            || $todo->assigned_to_id === $user->id
            || !$todo->is_private;
    }

    private function canUserModifyTodo(User $user, Todo $todo): bool
    {
        return $todo->author_id === $user->id
            || $todo->assigned_to_id === $user->id;
    }

    public function deleteTodo(int $id, User $user): bool
    {
        $todo = $this->getTodoById($id, $user);

        if (!$this->canUserModifyTodo($user, $todo)) {
            throw new AuthorizationException('You can only delete your own todos');
        }

        return $this->todoRepository->delete($todo);
    }

    public function completeTodo(int $id, User $user): Todo
    {
        $todo = $this->getTodoById($id, $user);

        if (!$this->canUserModifyTodo($user, $todo)) {
            throw new AuthorizationException('You can only complete your own todos or assigned to you');
        }

        $todo->markAsCompleted();
        return $todo->fresh(['author', 'assignedTo']);
    }

    public function archiveTodo(int $id, User $user): Todo
    {
        $todo = $this->getTodoById($id, $user);

        if (!$this->canUserModifyTodo($user, $todo)) {
            throw new AuthorizationException('You can only archive your own todos');
        }

        $todo->markAsArchived();
        return $todo->fresh(['author', 'assignedTo']);
    }

    public function getTodosByStatus(User $user, string $status): Collection
    {
        return $this->todoRepository->getByStatus($user, $status);
    }

    public function getTodosByPriority(User $user, string $priority): Collection
    {
        return $this->todoRepository->getByPriority($user, $priority);
    }

    public function getAssignedTodos(User $user): Collection
    {
        return $this->todoRepository->getAssignedToUser($user);
    }

    public function getCreatedTodos(User $user): Collection
    {
        return $this->todoRepository->getCreatedByUser($user);
    }
}
