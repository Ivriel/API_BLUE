<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory,UUID;

    protected $fillable = [
        'store_balance_id',
        'amount',
        'bank_account_name',
        'bank_account_number',
        'bank_name',
        'proof',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->whereHas('storeBalance.store', function ($q) use ($search) {
            $q->where('name', 'like', '%'.$search.'%');
        });
    }

    public function storeBalance()
    {
        return $this->belongsTo(StoreBalance::class);
    }
}
