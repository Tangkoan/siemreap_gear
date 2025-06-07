<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    //
    protected $guarded = [];


    // បង្កើត សម្រាប់ Join
    public function category(){
        return $this->belongsTO(Category::class,'category_id','id');
    }

    // បង្កើត សម្រាប់ Join
    public function supplier(){
        return $this->belongsTO(Supplier::class,'supplier_id','id');
    }


    // បង្កើត សម្រាប់ Join
    public function brand(){
        return $this->belongsTO(Brand::class,'brand_id','id');
    }

    // បង្កើត សម្រាប់ Join
    public function unit(){
        return $this->belongsTO(Unit::class,'unit_id','id');
    }
}
