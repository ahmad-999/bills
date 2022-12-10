<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Export>
 */
class ExportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'material_price' =>fake()->numberBetween(1,100),
            'wages' =>fake()->numberBetween(1,100),
            'details' => fake()->text(20),
            'opration_date' => fake()->dateTimeBetween('-1 days','+1 days'),
            'customer_id'=>1
        ];
    }
}
