<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class StoreBallanceHistory extends Model
{
    use UUID;

    protected $fillable = [
        'store_balance_id',
        'type',
        'reference_id',
        'reference_type',
        'amount',
        'remarks',
    ];

    public function storeBallance()
    {
        return $this->belongsTo(StoreBallance::class);
    }
}
