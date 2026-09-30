<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('likes')) {
            Schema::create('likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('movie_id')->nullable();
                $table->unsignedBigInteger('review_id')->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'review_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};