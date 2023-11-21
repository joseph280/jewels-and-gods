<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Token;
use App\Models\StakedItem;
use App\Http\Requests\StakeRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Services\ContractApiManagerInterface;

class StakeController extends Controller
{
    public function stake(string $asset_id, StakeRequest $request, ContractApiManagerInterface $api): RedirectResponse
    {
        $asset = $api->asset(assetId: $asset_id);
        $item = Item::where('template_id', $asset->templateId)->first();
        $token = Token::find($request->get('token_id'));
        $user = Auth::user();

        if (! $item) {
            return redirect(route('home'))
                ->withErrors(['item_id' => 'Item does not exist']);
        }

        if ($asset->owner !== $user->account_id) {
            return redirect(route('home'))
                ->withErrors(['asset_id' => 'You are not the owner of this asset']);
        }

        $currentStakedItemsCount = StakedItem::query()
            ->where('user_id', Auth::id())
            ->whereRelation('item', 'level', '>=', $item->level)
            ->whereNull('claimed_at')
            ->count();

        if ($currentStakedItemsCount === 3) {
            return redirect(route('home'))
                ->withErrors(['asset_id' => 'You cannot have more than 3 jewels staked with the same level.']);
        }

        if ($stakedItem = StakedItem::isStaking($asset_id)->first()) {
            if ($stakedItem->user_id !== $user->id && $asset->owner === $user->account_id) {
                $stakedItem->update([
                    'user_id' => $user->id,
                ]);

                return redirect(route('home'));
            }

            return redirect(route('home'))
                ->withErrors(['asset_id' => 'Asset is already in staking']);
        }

        StakedItem::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'token_id' => $token->id,
            'asset_id' => $asset->assetId,
            'staked_at' => now(),
        ]);

        return redirect(route('home'));
    }

    public function unstake(string $asset_id, ContractApiManagerInterface $api): RedirectResponse
    {
        $asset = $api->asset(assetId: $asset_id);

        if ($asset->owner !== auth()->user()->account_id) {
            return redirect(route('home'))
                ->withErrors(['asset_id' => 'You are not the owner of this asset']);
        }

        $stakedItem = StakedItem::where('asset_id', $asset_id)
            ->where('claimed_at', null)
            ->first();

        $balance = $stakedItem->calculateBalance();

        $stakedItem->update([
            'balance' => $balance,
            'claimed_at' => now(),
        ]);

        $wallet = Auth::user()
            ->wallets()
            ->where('token_id', $stakedItem->token_id)
            ->first();

        $wallet->update([
            'balance' => $wallet->balance + $balance,
        ]);

        return redirect(route('home'));
    }

    public function claimAll(): RedirectResponse
    {
        $wallets = Auth::user()
            ->wallets()
            ->get();

        StakedItem::query()
            ->where('user_id', Auth::user()->id)
            ->where('claimed_at', null)
            ->with('item')
            ->get()
            ->each(function ($stakedItem) use ($wallets) {
                $balance = $stakedItem->calculateBalance();

                $stakedItem->update([
                    'balance' => $balance,
                    'claimed_at' => now(),
                ]);

                $wallet = $wallets
                    ->where('token_id', $stakedItem->token_id)
                    ->first();

                $wallet->update([
                    'balance' => $wallet->balance + $balance,
                ]);

                StakedItem::create([
                    'user_id' => Auth::user()->id,
                    'item_id' => $stakedItem->item_id,
                    'token_id' => $stakedItem->token_id,
                    'asset_id' => $stakedItem->asset_id,
                    'staked_at' => now(),
                ]);
            });

        return redirect(route('home'));
    }
}
