<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('color')->default('#FFFFFF');
            $table->string('priority')->nullable()->default(null);
            $table->string('status')->nullable()->default(null);
            $table->foreignId('author_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->softDeletes();
            $table->foreignId('assigned_to_id')
                ->constrained('users')
                ->onDelete('set null');
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_archived')->default(false);
            $table->boolean('is_private')->default(false);
            $table->timestamp('due_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
