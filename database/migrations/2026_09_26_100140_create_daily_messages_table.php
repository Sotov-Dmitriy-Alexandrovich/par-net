<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_messages', function (Blueprint $table) {
            $table->id();
            $table->integer('day_number')->unique(); // 1-90
            $table->string('title');
            $table->text('text');
            $table->boolean('is_milestone')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_messages');
    }
};
