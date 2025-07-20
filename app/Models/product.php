<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    //
    protected $guarded = [];


    // បង្កើត សម្រាប់ Join
    // public function category(){
    //     return $this->belongsTO(Category::class,'category_id','id');
    // }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // បង្កើត សម្រាប់ Join
    public function supplier(){
        return $this->belongsTO(Supplier::class,'supplier_id','id');
    }

    public function condition(){
        return $this->belongsTO(Condition::class,'condition_id','id');
    }


    public function orderDetails()
    {
        return $this->hasMany(Orderdetails::class, 'product_id', 'id');
    }

    


}
