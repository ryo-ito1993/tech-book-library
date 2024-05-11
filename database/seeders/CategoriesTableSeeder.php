<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'HTML',
            'CSS',
            'JavaScript',
            'PHP',
            'Ruby',
            'Python',
            'Java',
            'Vue.js',
            'React',
            'TypeScript',
            'SQL',
            'DB',
            'Docker',
            'Git',
            'AWS',
            'Linux',
            'プログラミング',
            'インフラ',
            '品質保証/テスト',
            'ネットワーク',
            'アーキテクチャ',
            'セキュリティ',
            'AI',
            'ビジネス',
            'その他',
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
