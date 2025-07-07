<?php

namespace App\Models;

use App\Enum\TodoPriority;
use App\Enum\TodoStatus;
use Database\Factories\TodoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todo extends Model
{
    /** @use HasFactory<TodoFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'color',
        'priority',
        'status',
        'author_id',
        'assigned_to_id',
        'due_date',
        'is_private',
        'completed_at',
        'archived_at'
    ];

    protected $casts = [
        'priority' => TodoPriority::class,
        'status' => TodoStatus::class,
        'is_completed' => 'boolean',
        'is_archived' => 'boolean',
        'is_private' => 'boolean',
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
        'archived_at' => 'datetime'
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => TodoStatus::COMPLETED,
            'is_completed' => true,
            'completed_at' => now()
        ]);
    }

    public function markAsArchived(): void
    {
        $this->update([
            'status' => TodoStatus::ARCHIVED,
            'is_archived' => true,
            'archived_at' => now()
        ]);
    }
}
