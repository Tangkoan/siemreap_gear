<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
   
    protected $fillable = [
        'name',
        'email',
        'phone',
        'notes',
    ];

 // ទំនាក់ទំនងទៅ products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // ទំនាក់ទំនងទៅ purchases
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
