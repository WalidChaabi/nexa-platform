<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    protected $fillable = [
        'message',
        'date_start',
    ];

    protected function casts(): array
    {
        return [
            'date_start' => 'integer',
        ];
    }

    public function messageCampaigns(): HasMany
    {
        return $this->hasMany(MessageCampaign::class);
    }
}
