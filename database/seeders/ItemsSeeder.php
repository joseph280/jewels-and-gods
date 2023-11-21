<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Item::factory()->create([
            'name' => 'Clay ring',
            'template_id' => '411776',
            'staking_factor' => 0.3,
            'type' => 'ring',
            'level' => 1,
            'img_url' => '/assets/items/clay-ring.png',
        ]);

        Item::factory()->create([
            'name' => 'Wood ring',
            'template_id' => '411778',
            'staking_factor' => 0.5,
            'type' => 'ring',
            'level' => 1,
            'img_url' => '/assets/items/wood-ring.png',
        ]);

        Item::factory()->create([
            'name' => 'Resin ring',
            'template_id' => '411777',
            'staking_factor' => 0.8,
            'type' => 'ring',
            'level' => 1,
            'img_url' => '/assets/items/resin-ring.png',
        ]);

        Item::factory()->create([
            'name' => 'Aluminum ring',
            'template_id' => '411774',
            'staking_factor' => 1.7,
            'type' => 'ring',
            'level' => 1,
            'img_url' => '/assets/items/aluminum-ring.png',
        ]);

        Item::factory()->create([
            'name' => 'Iron ring',
            'template_id' => '327623',
            'staking_factor' => 1.0,
            'type' => 'ring',
            'level' => 2,
            'img_url' => '/assets/items/iron-ring.png',
        ]);

        Item::factory()->create([
            'name' => 'Copper ring',
            'template_id' => '327622',
            'staking_factor' => 1.5,
            'type' => 'ring',
            'level' => 2,
            'img_url' => '/assets/items/copper-ring.png',
        ]);

        Item::factory()->create([
            'name' => 'Silver ring',
            'template_id' => '327624',
            'staking_factor' => 2.5,
            'type' => 'ring',
            'level' => 2,
            'img_url' => '/assets/items/silver-ring.png',
        ]);

        Item::factory()->create([
            'name' => 'Gold ring',
            'template_id' => '233236',
            'staking_factor' => 5.0,
            'type' => 'ring',
            'level' => 2,
            'img_url' => '/assets/items/gold-ring.png',
        ]);

        Item::factory()->create([
            'name' => 'Copper necklace',
            'template_id' => '336609',
            'staking_factor' => 2.5,
            'type' => 'necklace',
            'level' => 2,
            'img_url' => '/assets/items/copper-necklace.png',
        ]);

        Item::factory()->create([
            'name' => 'Gold necklace',
            'template_id' => '336610',
            'staking_factor' => 7.5,
            'type' => 'necklace',
            'level' => 2,
            'img_url' => '/assets/items/gold-necklace.png',
        ]);

        Item::factory()->create([
            'name' => 'Iron Sapphire ring',
            'template_id' => '327629',
            'staking_factor' => 3.0,
            'type' => 'ring',
            'level' => 3,
            'img_url' => '/assets/items/iron-sapphire-ring.png',
        ]);

        Item::factory()->create([
            'name' => 'Copper Onyx ring',
            'template_id' => '327628',
            'staking_factor' => 4.2,
            'type' => 'ring',
            'level' => 3,
            'img_url' => '/assets/items/copper-onyx-ring.png',
        ]);

        Item::factory()->create([
            'name' => 'Silver Emerald ring',
            'template_id' => '327627',
            'staking_factor' => 7.8,
            'type' => 'ring',
            'level' => 3,
            'img_url' => '/assets/items/silver-emerald-ring.png',
        ]);

        Item::factory()->create([
            'name' => 'Gold Diamond ring',
            'template_id' => '233257',
            'staking_factor' => 15.0,
            'type' => 'ring',
            'level' => 3,
            'img_url' => '/assets/items/gold-diamond-ring.png',
        ]);

        Item::factory()->create([
            'name' => 'Copper Ruby necklace',
            'template_id' => '336608',
            'staking_factor' => 8.0,
            'type' => 'necklace',
            'level' => 3,
            'img_url' => '/assets/items/copper-ruby-necklace.png',
        ]);

        Item::factory()->create([
            'name' => 'Gold Emerald necklace',
            'template_id' => '336607',
            'staking_factor' => 20.0,
            'type' => 'necklace',
            'level' => 3,
            'img_url' => '/assets/items/gold-emeral-necklace.png',
        ]);

        Item::factory()->create([
            'name' => 'Iron Red and Blue ring',
            'template_id' => '336612',
            'staking_factor' => 10.0,
            'type' => 'ring',
            'level' => 4,
            'img_url' => '/assets/items/iron-red-blue-ring.png',
        ]);

        Item::factory()->create([
            'name' => 'Sky ring',
            'template_id' => '336606',
            'staking_factor' => 70.0,
            'type' => 'ring',
            'level' => 4,
            'img_url' => '/assets/items/sky-ring.png',
        ]);

        Item::factory()->create([
            'name' => 'Triple Emerald necklace',
            'template_id' => '336605',
            'staking_factor' => 60.0,
            'type' => 'necklace',
            'level' => 4,
            'img_url' => '/assets/items/triple-emerald-necklace.png',
        ]);
    }
}
