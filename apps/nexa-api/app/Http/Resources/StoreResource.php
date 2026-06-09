<?php

namespace App\Http\Resources;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Store */
class StoreResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'activity_id' => $this->activity_id,
            'name' => $this->name,
            'phone' => $this->phone,
            'contact' => $this->contact,
            'address' => $this->address,
            'status' => $this->status,
            'invoice_message' => $this->invoice_message,
            'sender_id' => $this->sender_id,
            'owner' => $this->whenLoaded('owner', fn () => [
                'id' => $this->owner->id,
                'full_name' => $this->owner->full_name,
                'email' => $this->owner->email,
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
