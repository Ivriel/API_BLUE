<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use UUID;

    protected $fillable = [
        'store_balance_id',
        'amount',
        'bank_account_name',
        'bank_account_number',
        'bank_name',
        'status',
    ];

    public function storeBallance()
    {
        return $this->belongsTo(StoreBallance::class);
    }
}
