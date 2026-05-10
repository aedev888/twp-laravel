<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Taxonomy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Taxonomies (Categories)
        $categories = [
            ['en' => 'Themes', 'ru' => 'Темы'],
            ['en' => 'Plugins', 'ru' => 'Плагины'],
            ['en' => 'Elementor Kits', 'ru' => 'Elementor Киты'],
        ];

        $createdCategories = [];
        foreach ($categories as $cat) {
            $createdCategories[] = Taxonomy::create([
                'name' => $cat,
                'slug' => Str::slug($cat['en']),
                'type' => 'category',
            ]);
        }

        // 2. Create Products
        $products = [
            [
                'title' => ['en' => 'Ultra Agency Pro Theme', 'ru' => 'Ultra Agency Pro Тема'],
                'description' => ['en' => 'A high-performance WordPress theme designed specifically for modern digital agencies and creative studios.', 'ru' => 'Высокопроизводительная тема WordPress, разработанная специально для современных цифровых агентств.'],
                'price_type' => 'premium',
                'version' => '2.4.5',
                'category_index' => 0, // Themes
            ],
            [
                'title' => ['en' => 'Smart SEO Optimizer', 'ru' => 'Smart SEO Оптимизатор'],
                'description' => ['en' => 'Lightweight and powerful SEO plugin to boost your rankings without slowing down your site.', 'ru' => 'Легкий и мощный SEO-плагин для повышения ваших позиций без замедления сайта.'],
                'price_type' => 'free',
                'version' => '1.2.0',
                'category_index' => 1, // Plugins
            ],
            [
                'title' => ['en' => 'Minimalist Portfolio Kit', 'ru' => 'Минималистичный Портфолио Кит'],
                'description' => ['en' => 'A stunning collection of Elementor templates for designers, photographers, and architects.', 'ru' => 'Потрясающая коллекция шаблонов Elementor для дизайнеров, фотографов и архитекторов.'],
                'price_type' => 'free',
                'version' => '1.0.0',
                'category_index' => 2, // Kits
            ],
            [
                'title' => ['en' => 'Mega Store eCommerce', 'ru' => 'Mega Store Магазин'],
                'description' => ['en' => 'Everything you need to build a professional online store with WooCommerce and Elementor.', 'ru' => 'Все необходимое для создания профессионального интернет-магазина с WooCommerce и Elementor.'],
                'price_type' => 'premium',
                'version' => '3.1.2',
                'category_index' => 0, // Themes
            ],
            [
                'title' => ['en' => 'Advanced Slider Pro', 'ru' => 'Advanced Slider Pro'],
                'description' => ['en' => 'The most advanced drag-and-drop slider builder for WordPress with 100+ templates.', 'ru' => 'Самый продвинутый визуальный конструктор слайдеров для WordPress с более чем 100 шаблонами.'],
                'price_type' => 'premium',
                'version' => '5.0.1',
                'category_index' => 1, // Plugins
            ],
            [
                'title' => ['en' => 'Real Estate Listing Kit', 'ru' => 'Кит для Недвижимости'],
                'description' => ['en' => 'Complete Elementor template kit for real estate agents and property managers.', 'ru' => 'Полный набор шаблонов Elementor для агентов по недвижимости и управляющих компаниях.'],
                'price_type' => 'premium',
                'version' => '1.1.0',
                'category_index' => 2, // Kits
            ],
        ];

        foreach ($products as $pData) {
            $catIndex = $pData['category_index'];
            unset($pData['category_index']);

            $product = Product::create([
                'title' => $pData['title'],
                'slug' => array_map(fn($t) => Str::slug($t), $pData['title']),
                'description' => $pData['description'],
                'price_type' => $pData['price_type'],
                'version' => $pData['version'],
                'status' => 'published',
                'published_at' => now(),
            ]);

            // Attach Category
            $product->taxonomies()->attach($createdCategories[$catIndex]->id);
        }
    }
}

