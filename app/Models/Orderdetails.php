<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orderdetails extends Model
{
    //


    protected $table = 'orderdetails';  // ត្រូវតាម migration
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
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
