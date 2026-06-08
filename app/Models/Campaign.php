<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    public $timestamps=false;
    
    protected $fillable = [
        'id','name','type'
    ];

    public function template(){
        return $this->belongsTo(Template::class,'template_id');
    }

    public function CampaignDetail(){
        return $this->belongsTo(CampaignDetail::class,'campaign_id');
    }

}


