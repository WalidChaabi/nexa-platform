<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Rdv */
class RdvResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'store_id' => $this->store_id,
            'event_id' => $this->event_id,
            'title' => $this->title,
            'start' => $this->start?->toIso8601String(),
            'status' => $this->status instanceof \BackedEnum ? $this->status->value : $this->status,
            'color' => $this->event_id,
            'customer' => $this->whenLoaded('customer', fn () => [
                'id' => $this->customer->id,
                'display_name' => trim("{$this->customer->firstname} {$this->customer->lastname}") ?: $this->customer->phone,
                'phone' => $this->customer->phone,
            ]),
            'store' => $this->whenLoaded('store', fn () => [
                'id' => $this->store->id,
                'name' => $this->store->name,
            ]),
        ];
    }
}
