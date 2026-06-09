<?php

namespace App\Models;

use Database\Factories\CrossProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrossProduct extends Model
{
    /** @use HasFactory<CrossProductFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'message',
        'days',
    ];

    protected function casts(): array
    {
        return [
            'days' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function smsRecords(): HasMany
    {
        return $this->hasMany(SmsCrossSelling::class, 'cross_product_id', 'product_id');
    }
}
