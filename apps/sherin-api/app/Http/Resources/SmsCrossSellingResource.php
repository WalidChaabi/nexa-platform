<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\SmsCrossSelling */
class SmsCrossSellingResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'store_id' => $this->store_id,
            'cross_product_id' => $this->cross_product_id,
            'sent_date' => $this->sent_date?->toIso8601String(),
            'credit' => $this->credit,
            'status' => $this->status,
            'store' => $this->whenLoaded('store', fn () => [
                'id' => $this->store->id,
                'name' => $this->store->name,
            ]),
            'product' => $this->whenLoaded('product', fn () => [
                'id' => $this->product->id,
                'label' => $this->product->label,
            ]),
        ];
    }
}
