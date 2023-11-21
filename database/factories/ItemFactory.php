<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'template_id' => $this->faker->uuid(),
            'name' => $this->faker->sentence(),
            'staking_factor' => $this->faker->randomFloat(1),
            'type' => $this->faker->word(),
            'level' => $this->faker->numberBetween(1, 4),
            'img_url' => $this->faker->imageUrl(),
        ];
    }
}
