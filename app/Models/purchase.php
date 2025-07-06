<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class purchase extends Model
{
    //
    protected $guarded = [];

    // បង្កើត សម្រាប់ Join
    public function supplier(){
        return $this->belongsTO(Supplier::class,'supplier_id','id');
    }

    public function product(){
        return $this->belongsTO(Product::class,'product_id','id');
    }
}
