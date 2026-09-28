<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. メインのテストユーザー
        User::factory()->create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // 2. コメント・レビュー交換用のユーザー（2人目）
        User::factory()->create([
            'name' => '映画太郎',
            'email' => 'taro@example.com',
            'password' => Hash::make('password123'),
        ]);

        // 3. コメント・レビュー交換用のユーザー（3人目）
        User::factory()->create([
            'name' => 'シネマ花子',
            'email' => 'hanako@example.com',
            'password' => Hash::make('password123'),
        ]);
    }
}