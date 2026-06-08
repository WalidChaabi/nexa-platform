<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsCrossSelling extends Model
{
    protected $table = 'sms_cross_selling';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id','customer_id','cross_product_id','sent_date','credit'
    ];
}
