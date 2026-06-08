<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    

    protected $table ="email_campaigns";

    protected $fillable = [
        'invoice_id',
        'store_id'  ,
        'status'  ,
    ];
}
