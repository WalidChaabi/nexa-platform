<?php

namespace App\Models;

use Database\Factories\CampaignFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    /** @use HasFactory<CampaignFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(CampaignDetail::class);
    }
}
