<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Model;

class Rdv extends Model
{
    use BelongsToStore;

    protected $fillable = [
        'store_id',
        'customer_id',
        'start',
        'end',
    ];
}
