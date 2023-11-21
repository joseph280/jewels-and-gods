<?php

namespace Tests\Feature;

use App\Models\Token;
use App\Models\User;
use Database\Seeders\TokensSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_new_user_if_it_does_not_exist()
    {
        $this->post(route('signin'), [
            'account_id' => 'test_wallet',
            'key_1' => 'test_key_1',
            'key_2' => 'test_key_2',
        ]);

        $newUser = User::query()
            ->where('account_id', 'test_wallet')
            ->where('key_1', 'test_key_1')
            ->where('key_2', 'test_key_2')
            ->first();

        $this->assertNotNull($newUser);
    }

    public function test_it_redirects_to_home_page_after_signing_in()
    {
        $user = User::factory()->create();

        $this->post(route('signin'), [
            'account_id' => $user->account_id,
            'key_1' => $user->key_1,
            'key_2' => $user->key_2,
        ])
        ->assertRedirect(route('home'));

        $this->assertDatabaseCount('users', 1);
    }

    public function test_it_create_user_wallets_if_he_does_not_exist()
    {
        app(TokensSeeder::class)->run();

        $this->post(route('signin'), [
            'account_id' => 'test_wallet',
            'key_1' => 'test_key_1',
            'key_2' => 'test_key_2',
        ]);

        $user = User::where('account_id', 'test_wallet')->first();

        $this->assertCount(3, $user->wallets);

        $this->assertSame(
            [0.0, 0.0, 0.0],
            $user->wallets->pluck('balance')->toArray()
        );
    }

    public function test_it_does_not_create_user_wallets_if_he_does_exist()
    {
        app(TokensSeeder::class)->run();

        $user = User::factory()->create();

        Token::all()->each(function (Token $token) use ($user) {
            $user->wallets()->create([
                'token_id' => $token->id,
                'balance' => 10.1,
            ]);
        });

        $this->post(route('signin'), [
            'account_id' => 'test_wallet',
            'key_1' => 'test_key_1',
            'key_2' => 'test_key_2',
        ]);

        $this->assertCount(3, $user->wallets);

        $this->assertSame(
            [10.1, 10.1, 10.1],
            $user->wallets->pluck('balance')->toArray()
        );
    }
}
