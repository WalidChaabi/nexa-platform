<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreOffre extends Model
{
    // use HasFactory;

    protected $table    = "store_offer";

    protected $fillable = [

                            'store_id' ,
                            'offre_id' ,
                            'value' ,
                         ];

    public function store()
    {
        return $this->belongsTo('App\Models\Store');
    }   
    
    public function offre()
    {
        return $this->belongsTo('App\Models\Offre');
    }
}
