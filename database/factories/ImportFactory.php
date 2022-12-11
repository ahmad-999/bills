<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Import>
 */
class ImportFactory extends Factory
{
    public function definition()
    {
        return [
            'payment' =>$this->faker->numberBetween(1,100),
            'details' => $this->faker->text(20),
            'opration_date' => $this->faker->dateTimeBetween('-1 days','+1 days'),
            'customer_id'=>function () {
                $customer = Customer::inRandomOrder()->first();
                return $customer->id;
            },
        ];
    }
}
