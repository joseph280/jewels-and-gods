<?php

namespace App\DTOs;

use App\Models\StakedItem;

class Asset
{
    public function __construct(
        public string $assetId,
        public string $templateId,
        public string $owner,
        public string $name,
        public string $imgUrl,
        public float $stakeFactor,
        public StakedItem | null $stakedItem = null,
    ) {
    }
}
