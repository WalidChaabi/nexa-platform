<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable=[
        'store_id','number','description','table_status'
    ];
}
