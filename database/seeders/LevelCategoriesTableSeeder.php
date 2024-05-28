<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LevelCategory;

class LevelCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levelCategories = [
            '入門',
            '初級',
            '中級',
            '上級',
            'リファレンス/その他',
        ];

        foreach ($levelCategories as $levelCategory) {
            LevelCategory::create(['name' => $levelCategory]);
        }
    }
}
