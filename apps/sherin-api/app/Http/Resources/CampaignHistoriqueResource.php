<?php

namespace App\Http\Resources;

use App\Models\CampaignHistorique;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin CampaignHistorique */
class CampaignHistoriqueResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'camp_id' => $this->camp_id,
            'store_id' => $this->store_id,
            'customer_id' => $this->customer_id,
            'phone' => $this->phone,
            'code_red' => $this->code_red,
            'start_date' => $this->start_date?->toIso8601String(),
            'status' => $this->status,
            'is_payed' => $this->is_payed,
            'customer' => $this->whenLoaded('customer', fn () => [
                'id' => $this->customer->id,
                'display_name' => trim("{$this->customer->firstname} {$this->customer->lastname}") ?: $this->customer->phone,
            ]),
            'store' => $this->whenLoaded('store', fn () => [
                'id' => $this->store->id,
                'name' => $this->store->name,
            ]),
        ];
    }
}
