<?php

namespace App\Http\Resources;

use App\Models\CrossProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin CrossProduct */
class CrossProductResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'message' => $this->message,
            'days' => $this->days,
            'product' => $this->whenLoaded('product', fn () => [
                'id' => $this->product->id,
                'label' => $this->product->label,
                'store_id' => $this->product->store_id,
                'store' => $this->product->relationLoaded('store') ? [
                    'id' => $this->product->store->id,
                    'name' => $this->product->store->name,
                ] : null,
            ]),
        ];
    }
}
