<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Taxonomy;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => ['en' => 'Themes', 'ru' => 'Темы'],
                'slug' => ['en' => 'themes', 'ru' => 'temy'],
                'type' => 'category',
            ],
            [
                'name' => ['en' => 'Plugins', 'ru' => 'Плагины'],
                'slug' => ['en' => 'plugins', 'ru' => 'plaginy'],
                'type' => 'category',
            ],
            [
                'name' => ['en' => 'Elements', 'ru' => 'Элементы'],
                'slug' => ['en' => 'elements', 'ru' => 'elementy'],
                'type' => 'category',
            ],
        ];

        foreach ($categories as $cat) {
            Taxonomy::updateOrCreate(['slug->en' => $cat['slug']['en']], $cat);
        }
    }
}

