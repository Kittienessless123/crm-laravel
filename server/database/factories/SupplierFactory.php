<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition(): array
    {
        return [
            'product_id' => 'SUP-' . $this->faker->unique()->numerify('#####'),
            'name' => $this->faker->company(),
            'region' => $this->faker->region(),
            'city' => $this->faker->city(),
            'contact_info' => $this->faker->phoneNumber(),
            'is_active' => $this->faker->boolean(90),
            'metadata' => json_encode([
                'delivery_time' => $this->faker->randomElement(['1-3 дня', '3-7 дней', '1-2 недели']),
                'min_order' => $this->faker->randomElement([5000, 10000, 25000, 50000]),
            ]),
        ];
    }
}