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

    public function scopeSearch($query, $search)
    {
        $query->where(function ($query) use ($search) {
            $query->where('store_balance_id', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%")
                ->orWhere('reference_id', 'like', "%{$search}%")
                ->orWhere('reference_type', 'like', "%{$search}%")
                ->orWhere('amount', 'like', "%{$search}%")
                ->orWhere('remarks', 'like', "%{$search}%");
        });
    }

    public function storeBalance()
    {
        return $this->belongsTo(StoreBalance::class);
    }
}
