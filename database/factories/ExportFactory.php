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
            'material_price' => $this->faker->numberBetween(1, 100),
            'wages' => $this->faker->numberBetween(1, 100),
            'details' => $this->faker->text(20),
            'opration_date' => $this->faker->dateTimeBetween('-1 days', '+1 days'),
            'customer_id' => function () {
                $customer = Customer::inRandomOrder()->first();
                return $customer->id;
            },
        ];
    }
}
