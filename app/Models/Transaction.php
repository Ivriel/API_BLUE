<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory,UUID;

    protected $fillable = [
        'code',
        'address_id',
        'buyer_id',
        'store_id',
        'address',
        'city',
        'postal_code',
        'shipping',
        'shipping_type',
        'shipping_cost',
        'tracking_number',
        'delivery_proof',
        'delivery_status',
        'tax',
        'grand_total',
        'payment_status',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where('code', 'like', '%'.$search.'%')
            ->orWhere('city', 'like', '%'.$search.'%')
            ->orWhere('payment_status', 'like', '%'.$search.'%')
            ->orWhere('address', 'like', '%'.$search.'%')
           // Di file Transaction.php pada function scopeSearch
            ->orWhereHas('buyer', function ($query) use ($search) {
                $query->where('buyers.user_id', 'like', '%'.$search.'%'); // Tambahkan nama tabelnya
            })
            ->orWhereHas('store', function ($query) use ($search) {
                $query->where('stores.name', 'like', '%'.$search.'%'); // Tambahkan nama tabelnya
            });
    }

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
