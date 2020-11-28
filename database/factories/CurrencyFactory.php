<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Currency::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'internal_id' => $this->faker->unique()->randomNumber(8),
            'number' => $this->faker->unique()->randomNumber(3),
            'code' => $this->faker->unique()->currencyCode,
            'name' => $this->faker->country . ' currency',
        ];
    }
}
