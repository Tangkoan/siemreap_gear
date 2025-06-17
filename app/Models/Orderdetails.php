<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orderdetails extends Model
{
    //
    protected $guarded = [];

    // public function product(){
    //     return $this->belongsTo(Product::class,'product_id','id');
    // }

    // public function products()
    // {
    //     return $this->hasMany(Product::class);
    // }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
