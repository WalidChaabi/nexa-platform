<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Database\Factories\CampaignDetailFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampaignDetail extends Model
{
    /** @use HasFactory<CampaignDetailFactory> */
    use BelongsToStore, HasFactory;

    protected $table = 'campaign_details';

    protected $fillable = [
        'campaign_id',
        'store_id',
        'filter_id',
        'product_id',
        'reduction',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'reduction' => 'integer',
            'active' => 'integer',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function filter(): BelongsTo
    {
        return $this->belongsTo(Filter::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function messageCampaigns(): HasMany
    {
        return $this->hasMany(MessageCampaign::class);
    }
}
