<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('review_id'); // ここを review_id に戻します
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('movie_id');
            $table->string('movie_title');
            $table->string('poster_path')->nullable();
            $table->integer('rating');
            $table->string('moods');
            $table->text('comment');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};