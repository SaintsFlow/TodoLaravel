<?php

namespace App\Contracts;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TodoRepositoryInterface
{
    public function findById(int $id): ?Todo;

    public function getAllForUser(User $user, array $filters = []): LengthAwarePaginator;

    public function getByStatus(User $user, string $status): Collection;

    public function getByPriority(User $user, string $priority): Collection;

    public function create(array $data): Todo;

    public function update(Todo $todo, array $data): Todo;

    public function delete(Todo $todo): bool;

    public function getAssignedToUser(User $user): Collection;

    public function getCreatedByUser(User $user): Collection;
}
