<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageCampaign extends Model
{
    protected $fillable = [
        'message_id',
        'campaign_detail_id',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    public function campaignDetail(): BelongsTo
    {
        return $this->belongsTo(CampaignDetail::class);
    }
}
