<?php

namespace Tests\Feature;

use App\DTOs\Asset;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Token;
use App\Models\StakedItem;
use Mockery\MockInterface;
use Database\Seeders\ItemsSeeder;
use Database\Seeders\TokensSeeder;
use App\Services\ContractApiManagerInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StakeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user);

        app(ItemsSeeder::class)->run();
        app(TokensSeeder::class)->run();

        Token::all()->each(function (Token $token) {
            $this->user->wallets()->create([
                'token_id' => $token->id,
                'balance' => 0,
            ]);
        });
    }

    public function test_it_can_put_in_stake_an_asset()
    {
        $item = Item::first();
        $token = Token::first();

        $this->mock(ContractApiManagerInterface::class, function (MockInterface $mock) use ($item) {
            $mock->shouldReceive('asset')->andReturn(new Asset(
                assetId: 'test_asset',
                owner: $this->user->account_id,
                templateId: $item->template_id,
                name: $item->name,
                imgUrl: $item->img_url,
                stakeFactor: $item->staking_factor,
            ));
        });

        $this->post(route('stake', ['asset_id' => 'test_asset']), [
            'token_id' => $token->id,
            'template_id' => $item->template_id,
        ])
            ->assertRedirect(route('home'))
            ->assertSessionHasNoErrors();

        $stakedItem = StakedItem::query()
            ->where('user_id', $this->user->id)
            ->where('token_id', $token->id)
            ->where('item_id', $item->id)
            ->where('asset_id', 'test_asset')
            ->first();

        $this->assertNotNull($stakedItem);
    }

    public function test_it_cannot_stake_more_than_3_items_with_same_level_in_same_token()
    {
        $items = Item::query()->where('level', 1)->get();
        $itemsWithStake = $items->take(3);
        $itemToStake = $items->whereNotIn('id', $itemsWithStake->pluck('id'))->first();
        $token = Token::first();

        $itemsWithStake->each(function (Item $item) use ($token) {
            StakedItem::factory()
                ->for($item)
                ->for($token)
                ->for($this->user)
                ->create([
                    'asset_id' => $item->id,
                    'staked_at' => now()->subDays(3),
                ]);
        });

        $this->mock(ContractApiManagerInterface::class, function (MockInterface $mock) use ($itemToStake) {
            $mock->shouldReceive('asset')->andReturn(new Asset(
                assetId: 'test_asset',
                owner: $this->user->account_id,
                templateId: $itemToStake->template_id,
                name: $itemToStake->name,
                imgUrl: $itemToStake->img_url,
                stakeFactor: $itemToStake->staking_factor,
            ));
        });

        $this->post(route('stake', ['asset_id' => 'test_asset']), [
            'token_id' => $token->id,
            'template_id' => $itemToStake->template_id,
        ])
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors(['asset_id' => 'You cannot have more than 3 jewels staked with the same level.']);

        $this->assertCount(3, StakedItem::all());
    }

    public function test_it_cannot_stake_an_already_staked_item()
    {
        $item = Item::first();
        $token = Token::first();
        StakedItem::factory()
            ->for($item)
            ->for($token)
            ->for($this->user)
            ->create([
                'asset_id' => 'test_asset',
                'staked_at' => now()->subDays(3),
            ]);

        $this->mock(ContractApiManagerInterface::class, function (MockInterface $mock) use ($item) {
            $mock->shouldReceive('asset')->andReturn(new Asset(
                assetId: 'test_asset',
                owner: $this->user->account_id,
                templateId: $item->template_id,
                name: $item->name,
                imgUrl: $item->img_url,
                stakeFactor: $item->staking_factor,
            ));
        });

        $this->post(route('stake', ['asset_id' => 'test_asset']), [
            'token_id' => $token->id,
            'template_id' => $item->template_id,
        ])
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors(['asset_id' => 'Asset is already in staking']);

        $stakedItems = StakedItem::query()
            ->where('user_id', $this->user->id)
            ->where('token_id', $token->id)
            ->where('item_id', $item->id)
            ->where('asset_id', 'test_asset')
            ->get();

        $this->assertCount(1, $stakedItems);
    }

    public function test_it_cannot_stake_if_auth_user_is_not_the_owner()
    {
        $item = Item::first();
        $token = Token::first();

        $this->mock(ContractApiManagerInterface::class, function (MockInterface $mock) use ($item) {
            $mock->shouldReceive('asset')->andReturn(new Asset(
                assetId: 'test_asset',
                owner: 'another_owner',
                templateId: $item->template_id,
                name: $item->name,
                imgUrl: $item->img_url,
                stakeFactor: $item->staking_factor,
            ));
        });

        $this->post(route('stake', ['asset_id' => 'test_asset']), [
            'token_id' => $token->id,
            'template_id' => $item->template_id,
        ])
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors(['asset_id' => 'You are not the owner of this asset']);

        $this->assertCount(0, StakedItem::all());
    }

    public function test_it_unstakes_the_reward()
    {
        $item = Item::first();
        $token = Token::first();
        StakedItem::factory()
            ->for($item)
            ->for($token)
            ->for($this->user)
            ->create([
                'asset_id' => 'test_asset',
                'staked_at' => now()->subDays(3),
            ]);

        $this->mock(ContractApiManagerInterface::class, function (MockInterface $mock) use ($item) {
            $mock->shouldReceive('asset')->andReturn(new Asset(
                assetId: 'test_asset',
                owner: $this->user->account_id,
                templateId: $item->template_id,
                name: $item->name,
                imgUrl: $item->img_url,
                stakeFactor: $item->staking_factor,
            ));
        });

        $this->post(route('unstake', ['asset_id' => 'test_asset']))
            ->assertRedirect(route('home'))
            ->assertSessionHasNoErrors();

        $stakedItem = StakedItem::first();

        $this->assertNotNull($stakedItem->claimed_at);
        $this->assertEquals(
            $token->stake_power * $item->staking_factor * $stakedItem->claimed_at->diffInHours($stakedItem->staked_at),
            $stakedItem->balance
        );
        $this->assertEquals(
            $stakedItem->balance,
            $this->user->wallets()->where('token_id', $token->id)->first()->balance
        );
    }

    public function test_it_cannot_unstake_the_reward_if_auth_user_is_not_the_owner()
    {
        $item = Item::first();
        $token = Token::first();
        StakedItem::factory()
            ->for($item)
            ->for($token)
            ->create([
                'asset_id' => 'test_asset',
                'staked_at' => now()->subDays(3),
            ]);

        $this->mock(ContractApiManagerInterface::class, function (MockInterface $mock) use ($item) {
            $mock->shouldReceive('asset')->andReturn(new Asset(
                assetId: 'test_asset',
                owner: 'other_user',
                templateId: $item->template_id,
                name: $item->name,
                imgUrl: $item->img_url,
                stakeFactor: $item->staking_factor,
            ));
        });

        $this->post(route('unstake', ['asset_id' => 'test_asset']))
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors(['asset_id' => 'You are not the owner of this asset']);

        $stakedItem = StakedItem::first();

        $this->assertNull($stakedItem->claimed_at);
        $this->assertNull($stakedItem->balance);
        $this->assertEquals(
            0,
            $this->user->wallets()->where('token_id', $token->id)->first()->balance
        );
    }

    public function test_it_updates_staked_item_if_owner_changed()
    {
        $otherUser = User::factory()->create();
        $item = Item::first();
        $token = Token::first();
        StakedItem::factory()
            ->for($item)
            ->for($token)
            ->for($otherUser)
            ->create([
                'asset_id' => 'test_asset',
                'staked_at' => now()->subDays(3),
            ]);

        $this->mock(ContractApiManagerInterface::class, function (MockInterface $mock) use ($item) {
            $mock->shouldReceive('asset')->andReturn(new Asset(
                assetId: 'test_asset',
                owner: $this->user->account_id,
                templateId: $item->template_id,
                name: $item->name,
                imgUrl: $item->img_url,
                stakeFactor: $item->staking_factor,
            ));
        });

        $this->post(route('stake', ['asset_id' => 'test_asset']), [
            'token_id' => $token->id,
            'template_id' => $item->template_id,
        ])
            ->assertRedirect(route('home'))
            ->assertSessionHasNoErrors();

        $this->assertCount(1, StakedItem::all());

        $stakedItem = StakedItem::first();

        $this->assertEquals($this->user->id, $stakedItem->user_id);
    }

    public function test_it_claims_all_the_reward()
    {
        $items = Item::query()->take(3)->get();
        $token = Token::first();

        $items->each(function (Item $item) use ($token) {
            StakedItem::factory()
                ->for($item)
                ->for($token)
                ->for($this->user)
                ->create([
                    'asset_id' => $item->id,
                    'staked_at' => now()->subDays(3),
                ]);
        });

        $this->mock(ContractApiManagerInterface::class, function (MockInterface $mock) use ($items) {
            $items->each(function (Item $item) use ($mock) {
                $mock->shouldReceive('asset')->andReturn(new Asset(
                    assetId: $item->id,
                    owner: $this->user->account_id,
                    templateId: $item->template_id,
                    name: $item->name,
                    imgUrl: $item->img_url,
                    stakeFactor: $item->staking_factor,
                ));
            });
        });

        $this->post(route('claim_all'))
            ->assertRedirect(route('home'))
            ->assertSessionHasNoErrors();

        $claimedStakedItems = StakedItem::query()
            ->with('item')
            ->whereNotNull('claimed_at')
            ->get();

        $newStakedItems = StakedItem::query()
            ->with('item')
            ->whereNotNull('claimed_at')
            ->get();

        $claimedStakedItems->each(function (StakedItem $stakedItem) {
            $this->assertNotNull($stakedItem->claimed_at);
            $this->assertEquals(
                $stakedItem->calculateBalance(),
                $stakedItem->balance
            );
        });

        $totalBalance = $claimedStakedItems->sum(fn (StakedItem $stakedItem) => $stakedItem->balance);

        $this->assertEquals(
            $totalBalance,
            $this->user->wallets()->where('token_id', $token->id)->first()->balance
        );

        $this->assertCount(3, $newStakedItems);
    }
}
