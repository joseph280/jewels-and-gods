<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Exceptions\InvalidCastException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\StakedItem
 *
 * @property int $id
 * @property int $user_id
 * @property int $item_id
 * @property int $token_id
 * @property string $asset_id
 * @property float|null $balance
 * @property \Illuminate\Support\Carbon $staked_at
 * @property \Illuminate\Support\Carbon|null $claimed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Item|null $item
 * @property-read \App\Models\Token|null $token
 * @property-read \App\Models\User|null $user
 *
 * @method static \Database\Factories\StakedItemFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|StakedItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StakedItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StakedItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|StakedItem whereAssetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakedItem whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakedItem whereClaimedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakedItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakedItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakedItem whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakedItem whereStakedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakedItem whereTokenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakedItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StakedItem whereUserId($value)
 * @mixin \Eloquent
 *
 * @method static Builder|StakedItem isStaking(string $asset_id)
 */
class StakedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'token_id',
        'asset_id',
        'balance',
        'staked_at',
        'claimed_at',
    ];

    protected $casts = [
        'claimed_at' => 'datetime',
        'staked_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function token(): BelongsTo
    {
        return $this->belongsTo(Token::class);
    }

    public function scopeIsStaking(Builder $query, string $asset_id): Builder
    {
        return $query
            ->where('asset_id', $asset_id)
            ->where('claimed_at', null);
    }

    /**
     * @throws InvalidCastException
     *
     * @return float
     */
    public function calculateBalance(): float
    {
        return $this->token->stake_power
            * $this->item->staking_factor
            * $this->getTimeDuration($this->staked_at, now());
    }

    /**
     * @param Carbon $from
     * @param Carbon $to
     *
     * @throws InvalidCastException
     *
     * @return int
     */
    protected function getTimeDuration(Carbon $from, Carbon $to): int
    {
        $durationInSeconds = $to->diffInSeconds($from) / 3600;

        return floor($durationInSeconds);
    }
}
