<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class StoreBallance extends Model
{
    use UUID;

    protected $fillable = [
        'store_id',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function storeBallanceHistories()
    {
        return $this->hasMany(StoreBallanceHistory::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }
}
