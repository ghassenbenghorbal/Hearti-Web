<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'secret_phrase' => "A1B2C3",
            // 'name' => $this->faker->name,
            'relative_name' => $this->faker->name,
            'relative_contact' => $this->faker->phoneNumber,
            'age' => $this->faker->numberBetween(18, 65),
            'address' => $this->faker->address,
            'bracelet_url' => "http://bracelet:3001",
            // 'user_id' => null,
        ];
    }
}
