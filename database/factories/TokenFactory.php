<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'token_id' => $this->faker->uuid(),
            'name' => $this->faker->word(),
            'img_url' => $this->faker->imageUrl(),
        ];
    }
}
