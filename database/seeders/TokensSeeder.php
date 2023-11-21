<?php

namespace Database\Seeders;

use App\Models\Token;
use Illuminate\Database\Seeder;

class TokensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Token::factory()->create([
            'token_id' => 'MISH',
            'name' => 'Mishra token',
            'img_url' => '/assets/tokens/mishra-coin.png',
            'stake_power' => 1,
        ]);

        Token::factory()->create([
            'token_id' => 'BYLD',
            'name' => 'Byldur token',
            'img_url' => '/assets/tokens/byldur-coin.png',
            'stake_power' => 1,
        ]);

        Token::factory()->create([
            'token_id' => 'TARR',
            'name' => 'Taurr token',
            'img_url' => '/assets/tokens/taurr-coin.png',
            'stake_power' => 1,
        ]);
    }
}
