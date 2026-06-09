<?php

namespace App\Http\Resources;

use App\Models\Filter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Filter */
class FilterResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'age_min' => $this->age_min,
            'age_max' => $this->age_max,
            'sexe' => $this->sexe,
            'points' => $this->points,
            'last_visit' => $this->last_visit,
            'heure_start' => $this->heure_start,
            'heure_end' => $this->heure_end,
        ];
    }
}
