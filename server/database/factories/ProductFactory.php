<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Seller;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $basePrice = $this->faker->randomFloat(2, 100, 50000);
        $margin = $this->faker->randomFloat(2, 10, 50);
        
        return [
            'product_id' => 'PRD-' . $this->faker->unique()->numerify('#####'),
            'product_name' => $this->faker->words(3, true),
            'sku' => strtoupper($this->faker->unique()->bothify('SKU-####-??')),
            'brand' => $this->faker->optional()->company(),
            'description' => $this->faker->optional()->paragraph(),
            
            'category_id' => Category::inRandomOrder()->first()->id,
            'unit_id' => Unit::inRandomOrder()->first()->id,
            
            'size' => $this->faker->optional()->randomElement([
                '100x50', '200x100', '50x50',
                '10мм', '20мм', '50мм',
                '1.5м', '2м', '3м',
            ]),
            
            // Цены
            'base_price' => $basePrice,
            'retail_price' => round($basePrice * (1 + $margin / 100), 2),
            'wholesale_price' => $this->faker->optional()->randomElement([
                round($basePrice * 0.85, 2),
                round($basePrice * 0.90, 2),
            ]),
            'currency' => $this->faker->randomElement(['RUB', 'RUB', 'RUB', 'USD']), // 75% RUB
            'margin' => $margin,
            
            // Связи
            'seller_id' => Seller::inRandomOrder()->first()->id,
            'supplier_id' => Supplier::inRandomOrder()->first()->id,
            
            // Статусы
            'is_active' => $this->faker->boolean(85),
            'is_available' => $this->faker->boolean(75),
            
            'photo_url' => $this->faker->optional()->imageUrl(640, 480, 'product'),
            'availability_date' => $this->faker->optional()->randomElement([
                'Май-Июнь', 'Июль-Август', 'Сентябрь', 'Круглый год',
            ]),
            'metadata' => json_encode([
                'weight' => $this->faker->randomFloat(2, 0.1, 100),
                'color' => $this->faker->safeColorName(),
                'material' => $this->faker->randomElement(['дерево', 'металл', 'пластик', 'бетон']),
            ]),
        ];
    }
}