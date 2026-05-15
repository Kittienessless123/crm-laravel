<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Unit;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Единицы измерения
        $units = [
            ['name' => 'Штуки', 'code' => 'pcs', 'symbol' => 'шт'],
            ['name' => 'Кубометры', 'code' => 'm3', 'symbol' => 'м³'],
            ['name' => 'Погонные метры', 'code' => 'running_m', 'symbol' => 'пог.м'],
            ['name' => 'Килограммы', 'code' => 'kg', 'symbol' => 'кг'],
            ['name' => 'Литр', 'code' => 'l', 'symbol' => 'л'],
            ['name' => 'Упаковка', 'code' => 'pack', 'symbol' => 'уп'],
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
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
