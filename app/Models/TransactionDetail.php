<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use UUID;

    protected $fillable = [
        'transaction_id',
        'product_id',
        'qty',
        'subtotal',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
