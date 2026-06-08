<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Model;

class Epingles extends Model
{
    use BelongsToStore;

    protected $table = 'epingles';

    public $timestamps = false;

    protected $fillable = [
        'store_id',
        'name',
        'color',
    ];
}
