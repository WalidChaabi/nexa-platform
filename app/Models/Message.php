<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'id',
        'message',
        'date_start',
        'type_envoi'
    ];
    public function campaignDetails()
    {
        return $this->hasMany(MessageCampaign::class, 'message_id');
    }
}
