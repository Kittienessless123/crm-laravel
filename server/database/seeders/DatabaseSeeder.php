<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Unit;
use App\Models\User;
use App\Models\Seller;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1 тестовый пользователь
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Единицы измерения
        $units = [
            ['name' => 'Штуки', 'short_name' => 'шт'],
            ['name' => 'Кубометры', 'short_name' => 'м³'],
            ['name' => 'Погонные метры', 'short_name' => 'пог.м'],
            ['name' => 'Килограммы', 'short_name' => 'кг'],
            ['name' => 'Литры', 'short_name' => 'л'],
            ['name' => 'Упаковка', 'short_name' => 'уп'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }

        // Категории
        $categories = [
            ['name' => 'Пиломатериалы', 'slug' => 'pilomaterialy'],
            ['name' => 'Стройматериалы', 'slug' => 'stroitelnye-materialy'],
            ['name' => 'Сантехника', 'slug' => 'santehnika'],
            ['name' => 'Электрика', 'slug' => 'elektrika'],
            ['name' => 'Инструменты', 'slug' => 'instrumenty'],
            ['name' => 'Крепёж', 'slug' => 'krepezh'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // 10 продавцов
        Seller::factory(10)->create();

        // 10 поставщиков
        Supplier::factory(10)->create();

        // 10 товаров
        Product::factory(10)->create();
    }
}