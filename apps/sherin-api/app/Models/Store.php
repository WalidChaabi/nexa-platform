<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_id',
        'logo',
        'name',
        'phone',
        'contact',
        'address',
        'base',
        'base_profit',
        'coeff',
        'tva',
        'status',
        'invoice_message',
        'allow_camp',
        'sender_id',
        'reste',
        'rendez_vous',
        'praticien',
        'Anniv',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
