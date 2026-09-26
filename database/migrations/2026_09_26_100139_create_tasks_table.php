<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->integer('day_number')->unique(); // 1-90
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['action', 'reflection', 'reward', 'sos'])->default('action');
            $table->string('icon', 10)->default('🎯');
            $table->tinyInteger('difficulty')->default(1); // 1-3
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
