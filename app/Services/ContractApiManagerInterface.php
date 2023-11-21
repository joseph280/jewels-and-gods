<?php

namespace App\Services;

use App\DTOs\Asset;
use Illuminate\Support\Collection;

interface ContractApiManagerInterface
{
    public function assets(string $accountId): Collection;

    public function asset(string $assetId): Asset;
}
