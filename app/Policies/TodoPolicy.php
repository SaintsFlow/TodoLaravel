<?php

namespace App\Policies;

use App\Models\Todo;
use App\Models\User;

class TodoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Пользователь может видеть свои Todo и назначенные ему
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Todo $todo): bool
    {
        return $todo->author_id === $user->id
            || $todo->assigned_to_id === $user->id
            || !$todo->is_private;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Любой авторизованный пользователь может создавать Todo
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Todo $todo): bool
    {
        return $todo->author_id === $user->id
            || $todo->assigned_to_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Todo $todo): bool
    {
        return $todo->author_id === $user->id; // Только автор может удалять
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Todo $todo): bool
    {
        return $todo->author_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Todo $todo): bool
    {
        return $todo->author_id === $user->id;
    }

    /**
     * Determine whether the user can complete the todo.
     */
    public function complete(User $user, Todo $todo): bool
    {
        return $todo->author_id === $user->id
            || $todo->assigned_to_id === $user->id;
    }

    /**
     * Determine whether the user can archive the todo.
     */
    public function archive(User $user, Todo $todo): bool
    {
        return $todo->author_id === $user->id;
    }
}
