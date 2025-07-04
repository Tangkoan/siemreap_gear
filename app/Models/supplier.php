<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
   
    protected $guarded = [];

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
