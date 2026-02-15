<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use UUID;

    protected $fillable = [
        'address_id',
        'buyer_id',
        'store_id',
        'address',
        'city',
        'postal_code',
        'shipping',
        'shipping_type',
        'shipping_cost',
        'tax',
        'grand_total',
        'payment_status',
    ];

    protected $casts = [
        'shipping_cost' => 'decimal:2',
        'tax' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }
}
