<?php

namespace Database\Factories;

use App\Models\CurrencyRate;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyRateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CurrencyRate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date' => $this->faker->unique()->date('Y-m-d'),
            'denomination' => $this->faker->randomElement([1, 100, 1000]),
            'value' => $this->faker->randomFloat(4, 1, 9999),
        ];
    }
}
