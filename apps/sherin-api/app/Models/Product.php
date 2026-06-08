<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use BelongsToStore, HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'store_id',
        'label',
        'price',
        'promotion_price',
        'quantite',
        'categorie_id',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'promotion_price' => 'float',
            'quantite' => 'integer',
        ];
    }
}
