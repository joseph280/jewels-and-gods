<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'account_id' => $this->faker->uuid(),
            'key_1' => $this->faker->uuid(),
            'key_2' => $this->faker->uuid(),
        ];
    }
}
