<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignHistorique extends Model
{
   
    protected $table ='campaign_historiques';
    protected $fillable = ['camp_id','invoice_id','store_id','customer_id','phone','code_red','start_date','status','expired_code'];


    public function  customer()
    {
        return $this->belongsTo('App\Models\Customer','customer_id');
    }

    public function campaign()
    {
        return $this->belongsTo('App\Models\Campaign','camp_id');
    }

    public function store()
    {
        return $this->belongs('App\Models\Store','store_id');
    }
    
}
