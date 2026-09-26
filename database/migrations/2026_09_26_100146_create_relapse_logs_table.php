<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('relapse_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('attempt_id')->constrained('quit_attempts')->onDelete('cascade');
            $table->integer('day_number_when_relapsed');
            $table->enum('trigger_type', ['stress', 'boredom', 'friends', 'advertising', 'other']);
            $table->text('what_happened');
            $table->text('what_i_could_do_differently');
            $table->text('lesson_learned')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('attempt_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relapse_logs');
    }
};
