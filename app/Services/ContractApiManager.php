<?php

namespace App\Services;

use Http;
use App\DTOs\Asset;
use App\Models\Item;
use App\Models\StakedItem;
use Illuminate\Support\Collection;

class ContractApiManager implements ContractApiManagerInterface
{
    public function __construct(
        protected string $baseUrl,
        protected string $collectionName,
    ) {
    }

    public function assets(string $accountId): Collection
    {
        $items = Item::all();
        $templateIds = $items->pluck('template_id');
        $stakedItems = StakedItem::query()
            ->where('user_id', auth()->id())
            ->where('claimed_at', null)
            ->with(['token', 'item'])
            ->get();

        $response = Http::get(
            sprintf('%s/atomicassets/v1/assets', $this->baseUrl),
            [
                'collection_name' => $this->collectionName,
                'owner' => $accountId,
                'template_whitelist' => $templateIds->join(','),
                'limit' => 1000,
            ]
        )->json();

        return collect(data_get($response, 'data', []))
            ->map(function (array $asset) use ($items, $stakedItems) {
                $templateId = data_get($asset, 'template.template_id');
                $template = $items->where('template_id', $templateId)->first();
                $assetId = data_get($asset, 'asset_id');

                $stakedItem = $stakedItems->where('asset_id', $assetId)->first();

                return new Asset(
                    assetId: $assetId,
                    templateId: data_get($asset, 'template.template_id'),
                    owner: data_get($asset, 'owner'),
                    name: $template->name,
                    imgUrl: $template->img_url,
                    stakeFactor: $template->staking_factor,
                    stakedItem: $stakedItem
                );
            });
    }

    public function asset(string $assetId): Asset
    {
        $response = Http::get(
            sprintf('%s/atomicassets/v1/assets/%s', $this->baseUrl, $assetId),
        )->json();

        $data = data_get($response, 'data');
        $templateId = data_get($data, 'template.template_id');

        $template = Item::where('template_id', $templateId)->first();

        return new Asset(
            assetId: data_get($data, 'asset_id'),
            templateId: data_get($data, 'template.template_id'),
            owner: data_get($data, 'owner'),
            name: $template->name,
            imgUrl: $template->img_url,
            stakeFactor: $template->staking_factor,
        );
    }
}
