<?php

namespace App\Http\Resources;

use App\Models\PaymentHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin PaymentHistory */
class PaymentHistoryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $services = json_decode($this->services, true);

        return [
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'cmd' => $this->cmd,
            'total_ht' => (float) $this->total_ht,
            'total_ttc' => (float) $this->total_ttc,
            'services' => is_array($services) ? $services : [],
            'status' => $this->status,
            'status_label' => $this->statusEnum()->label(),
            'created_at' => $this->created_at?->toIso8601String(),
            'owner' => $this->whenLoaded('owner', fn () => [
                'id' => $this->owner->id,
                'full_name' => $this->owner->full_name,
                'email' => $this->owner->email,
            ]),
        ];
    }
}
