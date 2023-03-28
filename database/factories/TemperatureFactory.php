<?php

namespace Database\Factories;

use App\Models\Temperature;
use Illuminate\Database\Eloquent\Factories\Factory;

class TemperatureFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Temperature::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "temperature" => random_int(60, 75),
            "created_at" => time() + random_int(-300000,100000)
        ];
    }
}
