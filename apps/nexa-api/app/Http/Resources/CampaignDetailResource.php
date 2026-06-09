<?php

namespace App\Http\Resources;

use App\Models\CampaignDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin CampaignDetail */
class CampaignDetailResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'campaign_id' => $this->campaign_id,
            'store_id' => $this->store_id,
            'product_id' => $this->product_id,
            'reduction' => $this->reduction,
            'active' => $this->active,
            'campaign' => $this->whenLoaded('campaign', fn () => new CampaignResource($this->campaign)),
            'filter' => $this->whenLoaded('filter', fn () => new FilterResource($this->filter)),
            'store' => $this->whenLoaded('store', fn () => [
                'id' => $this->store->id,
                'name' => $this->store->name,
            ]),
            'product' => $this->whenLoaded('product', fn () => $this->product ? [
                'id' => $this->product->id,
                'label' => $this->product->label,
            ] : null),
            'messages' => $this->whenLoaded('messageCampaigns', function () {
                return $this->messageCampaigns
                    ->loadMissing('message')
                    ->map(fn ($link) => [
                        'id' => $link->message?->id,
                        'message' => $link->message?->message,
                        'date_start' => $link->message?->date_start,
                    ])
                    ->values();
            }),
            'audience_count' => $this->when(
                isset($this->audience_count),
                fn () => $this->audience_count,
            ),
        ];
    }
}
