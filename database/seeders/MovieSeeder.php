<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        Movie::create([
            'title' => 'インセプション',
            'release_ym' => '2010-07',
            'director' => 'クリストファー・ノーラン',
            'synopsis' => '人の夢の中に潜入してアイデアを盗み出すSFアクション。',
        ]);

        Movie::create([
            'title' => 'ショーシャンクの空に',
            'release_ym' => '1994-09',
            'director' => 'フランク・ダラボン',
            'synopsis' => '冤罪で服役する男の希望と友情を描いたドラマ。',
        ]);
    }
}