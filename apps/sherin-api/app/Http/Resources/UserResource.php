<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'store_id' => $this->store_id,
            'role' => $this->role()->label(),
            'is_active' => $this->isActive(),
            'dashboard_path' => $this->role()->dashboardPath(),
        ];
    }
}
