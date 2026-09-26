<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('completed_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('attempt_id')->constrained('quit_attempts')->onDelete('cascade');
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->integer('day_number');
            $table->text('note')->nullable();
            $table->timestamp('completed_at')->useCurrent();

            $table->unique(['user_id', 'attempt_id', 'task_id']);
            $table->index(['attempt_id', 'day_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('completed_tasks');
    }
};
