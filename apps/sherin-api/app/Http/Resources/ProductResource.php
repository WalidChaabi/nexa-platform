<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'store_id' => $this->store_id,
            'label' => $this->label,
            'price' => (float) $this->price,
            'promotion_price' => (float) $this->promotion_price,
            'quantite' => $this->quantite,
        ];
    }
}
