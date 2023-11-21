<?php

namespace App\Http\Controllers;

use App\Http\Requests\SigninRequest;
use App\Models\Token;
use App\Models\User;
use Auth;

class AuthController extends Controller
{
    public function signin(SigninRequest $request)
    {
        $user = User::where('account_id', $request->get('account_id'))->first();

        if(!$user) {
            $user = User::create([
                'account_id' => $request->get('account_id'),
                'key_1' => $request->get('key_1'),
                'key_2' => $request->get('key_2'),
            ]);

            Token::all()->each(function (Token $token) use ($user) {
                $user->wallets()->create([
                    'token_id' => $token->id,
                    'balance' => 0,
                ]);
            });
        }

        Auth::login($user);

        return redirect()->intended(route('home'));
    }
}
