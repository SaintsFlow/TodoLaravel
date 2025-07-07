<?php

namespace App\Repositories;

use App\Contracts\TodoRepositoryInterface;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TodoRepository implements TodoRepositoryInterface
{
    public function findById(int $id): ?Todo
    {
        return Todo::with(['author', 'assignedTo'])->find($id);
    }

    public function getAllForUser(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = Todo::with(['author', 'assignedTo'])
            ->where(function ($q) use ($user) {
                $q->where('author_id', $user->id)
                    ->orWhere('assigned_to_id', $user->id)
                    ->orWhere('is_private', false);
            });

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                    ->orWhere('description', 'like', "%{$filters['search']}%");
            });
        }

        if (isset($filters['due_date_from'])) {
            $query->where('due_date', '>=', $filters['due_date_from']);
        }

        if (isset($filters['due_date_to'])) {
            $query->where('due_date', '<=', $filters['due_date_to']);
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate($filters['per_page'] ?? 15);
    }

    public function getByStatus(User $user, string $status): Collection
    {
        return Todo::with(['author', 'assignedTo'])
            ->where('status', $status)
            ->where(function ($q) use ($user) {
                $q->where('author_id', $user->id)
                    ->orWhere('assigned_to_id', $user->id)
                    ->orWhere('is_private', false);
            })
            ->get();
    }

    public function getByPriority(User $user, string $priority): Collection
    {
        return Todo::with(['author', 'assignedTo'])
            ->where('priority', $priority)
            ->where(function ($q) use ($user) {
                $q->where('author_id', $user->id)
                    ->orWhere('assigned_to_id', $user->id)
                    ->orWhere('is_private', false);
            })
            ->get();
    }

    public function create(array $data): Todo
    {
        return Todo::create($data);
    }

    public function update(Todo $todo, array $data): Todo
    {
        $todo->update($data);
        return $todo->fresh(['author', 'assignedTo']);
    }

    public function delete(Todo $todo): bool
    {
        return $todo->delete();
    }

    public function getAssignedToUser(User $user): Collection
    {
        return Todo::with(['author', 'assignedTo'])
            ->where('assigned_to_id', $user->id)
            ->get();
    }

    public function getCreatedByUser(User $user): Collection
    {
        return Todo::with(['author', 'assignedTo'])
            ->where('author_id', $user->id)
            ->get();
    }
}
