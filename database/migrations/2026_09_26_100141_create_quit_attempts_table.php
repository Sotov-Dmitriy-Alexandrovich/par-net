<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quit_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->enum('status', ['active', 'relapsed', 'completed'])->default('active');
            $table->integer('current_day')->default(0);
            $table->integer('total_clean_days')->default(0);
            $table->integer('relapse_count')->default(0);
            $table->tinyInteger('chosen_method')->default(1); // 1, 2 или 3
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quit_attempts');
    }
};
