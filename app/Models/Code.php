<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $fillable = [ 
        'id',
        'customer_id',
        'code_red' , 
        'status', 
    ];
    
}
