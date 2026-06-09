<?php

namespace App\Models;

use App\Enums\PaymentHistoryStatus;
use Database\Factories\PaymentHistoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentHistory extends Model
{
    /** @use HasFactory<PaymentHistoryFactory> */
    use HasFactory;

    protected $table = 'historique_paiements';

    protected $fillable = [
        'owner_id',
        'cmd',
        'total_ht',
        'total_ttc',
        'services',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'total_ht' => 'float',
            'total_ttc' => 'float',
            'status' => 'integer',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function statusEnum(): PaymentHistoryStatus
    {
        return PaymentHistoryStatus::tryFrom((int) $this->status) ?? PaymentHistoryStatus::Pending;
    }
}
