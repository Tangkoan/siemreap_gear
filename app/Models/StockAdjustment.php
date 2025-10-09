<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'related_id', 
        'type',
        'quantity',
        'before_quantity',
        'after_quantity',
        'notes',
    ];

    // Relationship ទៅ Product
    // public function product()
    // {
    //     return $this->belongsTo(Product::class);
    // }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Relationship ទៅ User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}