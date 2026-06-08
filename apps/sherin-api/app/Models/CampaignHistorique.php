<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Database\Factories\CampaignHistoriqueFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignHistorique extends Model
{
    /** @use HasFactory<CampaignHistoriqueFactory> */
    use BelongsToStore, HasFactory;

    protected $table = 'campaign_historiques';

    protected $fillable = [
        'camp_id',
        'invoice_id',
        'store_id',
        'customer_id',
        'phone',
        'code_red',
        'start_date',
        'status',
        'expired_code',
        'is_payed',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'status' => 'integer',
            'expired_code' => 'integer',
            'is_payed' => 'integer',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
