<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // movie_title カラムがなければ追加
            if (!Schema::hasColumn('reviews', 'movie_title')) {
                $table->string('movie_title')->nullable()->after('user_id');
            }
            // comment カラムがなければ追加
            if (!Schema::hasColumn('reviews', 'comment')) {
                $table->text('comment')->nullable()->after('rating');
            }
            // mood (または moods) カラムがなければ追加
            if (!Schema::hasColumn('reviews', 'mood')) {
                $table->string('mood')->nullable()->after('comment');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'movie_title')) {
                $table->dropColumn('movie_title');
            }
            if (Schema::hasColumn('reviews', 'comment')) {
                $table->dropColumn('comment');
            }
            if (Schema::hasColumn('reviews', 'mood')) {
                $table->dropColumn('mood');
            }
        });
    }
};