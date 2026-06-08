<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;
use App\Models\Store;
class Fond extends Model
{
    public $timestamps = false;

    protected $fillable = [ 'store_id' , 'encasement_type' ,'value', 'invoice_id' , 'encasement_date' ];

    public function invoice(){
        return  $this->belongsTo(Invoice::class,'invoice_id');
    }
    
    public function store(){
        return  $this->belongsTo(Store::class,'store_id');
    }
    
}
