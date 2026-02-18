<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreBalanceHistory extends Model
{
    use HasFactory,UUID;

    protected $fillable = [
        'store_balance_id',
        'type',
        'reference_id',
        'reference_type',
        'amount',
        'remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function storeBalance()
    {
        return $this->belongsTo(StoreBalance::class);
    }
}
