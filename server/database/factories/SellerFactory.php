<?php

namespace Database\Factories;

use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SellerFactory extends Factory
{
    protected $model = Seller::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'contact_info' => $this->faker->phoneNumber(),
            'registration_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'is_active' => $this->faker->boolean(80), // 80% активных
            'description' => $this->faker->sentence(),
            'metadata' => json_encode([
                'inn' => $this->faker->numerify('##########'),
                'address' => $this->faker->address(),
            ]),
        ];
    }
}