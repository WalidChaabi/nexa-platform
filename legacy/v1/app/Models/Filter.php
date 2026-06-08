<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;
use App\Models\Store;
class Filter extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'age_min' , 
        'age_max' , 
        'points' ,
        'last_visit', 
        'sexe' , 
        'heure_start', 
        'heure_end', 
        'active'
    ];
    
}
