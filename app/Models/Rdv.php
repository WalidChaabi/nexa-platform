<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rdv extends Model
{
    public $timestamps=false;
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'title', 'customer_id','event_id','store_id','start','status', 'expired'
   ];

   public function customer(){
    return $this->belongsTo(Customer::class,'customer_id');
   }
}
