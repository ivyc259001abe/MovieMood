<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('status')
                ->default('watched')
                ->after('mood');
        });

        // 既存の期待メモは、タグから判定して区別する
        DB::table('reviews')
            ->where('mood', 'like', '%しそう%')
            ->update(['status' => 'want_to_watch']);
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
