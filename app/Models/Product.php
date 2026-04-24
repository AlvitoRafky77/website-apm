<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'full_description',
        'price',
        'price_b2b',
        'min_order_b2c',
        'max_order_b2c',
        'min_order_b2b',
        'max_order_b2b',
        'unit',
        'weight',
        'stock_b2c',
        'stock_b2b',
        'image',
        'is_featured',
        'is_active',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
