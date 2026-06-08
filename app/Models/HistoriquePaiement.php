<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriquePaiement extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'owner_id','cmd','total_ht','total_ttc','services'
    ];

    public function owner(){
        return $this->belongsTo(User::class,'owner_id');
    }
   
}
