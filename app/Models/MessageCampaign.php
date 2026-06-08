<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageCampaign extends Model
{

    protected $fillable = [
        'campaign_detail_id',
        'message_id',
    ];
    public function CampaignDetail(){
        return $this->belongsTo(CampaignDetail::class,'campaign_detail_id','id');
    }
    public function messages(){
        return $this->belongsTo(Message::class,'message_id','id');
    }
}
