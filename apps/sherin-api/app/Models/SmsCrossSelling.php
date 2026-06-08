<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Database\Factories\SmsCrossSellingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsCrossSelling extends Model
{
    /** @use HasFactory<SmsCrossSellingFactory> */
    use BelongsToStore, HasFactory;

    protected $table = 'sms_cross_selling';

    protected $fillable = [
        'store_id',
        'cross_product_id',
        'sent_date',
        'credit',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'sent_date' => 'datetime',
            'credit' => 'integer',
            'status' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'cross_product_id');
    }
}
