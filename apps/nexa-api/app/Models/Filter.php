<?php

namespace App\Models;

use Database\Factories\FilterFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Filter extends Model
{
    /** @use HasFactory<FilterFactory> */
    use HasFactory;

    protected $fillable = [
        'age_min',
        'age_max',
        'sexe',
        'points',
        'last_visit',
        'heure_start',
        'heure_end',
    ];

    protected function casts(): array
    {
        return [
            'age_min' => 'integer',
            'age_max' => 'integer',
            'points' => 'integer',
            'last_visit' => 'integer',
        ];
    }

    public function campaignDetail(): HasOne
    {
        return $this->hasOne(CampaignDetail::class);
    }
}
