<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    // protected $guarded = [];

    protected $fillable = [
        'customer_id',
        'order_date',
        'order_status',
        'order_type',
        'discount',
        'total_products',
        'sub_total',
        'vat',
        'invoice_no',
        'total',
        'payment_status',
        'pay',
        'due',
        'exchange_rate_khr', // <--- បន្ថែមបន្ទាត់នេះ
    ];

    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id','id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class, 'order_id');
    }
    
    
}
