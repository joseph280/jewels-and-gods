<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use App\Models\Token;
use Illuminate\Database\Eloquent\Factories\Factory;

class StakedItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
            'token_id' => Token::factory(),
            'asset_id' => $this->faker->uuid(),
            'staked_at' => now(),
        ];
    }
}
