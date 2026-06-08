<?php

namespace App\Models;

use App\models\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignDetail extends Model
{
    
    protected $table ='campaign_details';
    protected $fillable = ['campaign_name','campaign_id','filter_id','store_id','product_id','reduction','active','nombre_client'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class,'campaign_id');
    }
    
    public function store()
    {
        return $this->belongsTo(Store::class,'store_id');
    }

    public function filter()
    {
        return $this->belongsTo(Filter::class,'filter_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function message_camp(){
        return $this->hasMany(MessageCampaign::class,'campaign_detail_id','id');
    }

}
