<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use UUID;

    protected $fillable = [
        'parent_id',
        'image',
        'name',
        'slug',
        'tagline',
        'description',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%'.$search.'%');
    }

    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id', 'id');
    }

    public function childrens()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
