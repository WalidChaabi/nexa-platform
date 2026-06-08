<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Invoice */
class InvoiceResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'store_id' => $this->store_id,
            'customer_id' => $this->customer_id,
            'employ_id' => $this->employ_id,
            'invoice_date' => $this->invoice_date?->toIso8601String(),
            'total' => $this->total,
            'total_ht' => $this->total_ht,
            'paid_amount' => $this->paid_amount,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'status_label' => $this->statusEnum()->label(),
            'reste' => $this->reste,
            'customer' => $this->whenLoaded('customer', fn () => [
                'id' => $this->customer->id,
                'firstname' => $this->customer->firstname,
                'lastname' => $this->customer->lastname,
                'phone' => $this->customer->phone,
            ]),
            'employer' => $this->whenLoaded('employer', fn () => [
                'id' => $this->employer->id,
                'full_name' => $this->employer->full_name,
            ]),
            'items' => InvoiceProductResource::collection($this->whenLoaded('items')),
            'store' => $this->whenLoaded('store', fn () => [
                'id' => $this->store->id,
                'name' => $this->store->name,
                'phone' => $this->store->phone,
                'address' => $this->store->address,
                'invoice_message' => $this->store->invoice_message,
            ]),
        ];
    }
}
