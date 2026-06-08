<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\InvoiceProduct */
class InvoiceProductResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'label' => $this->whenLoaded('product', fn () => $this->product?->label),
            'qte' => $this->qte,
            'price' => $this->price,
            'line_total' => round($this->qte * $this->price, 2),
        ];
    }
}
