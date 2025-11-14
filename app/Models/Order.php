<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ✅ ជំហានទី១៖ ត្រូវប្រាកដថាបានបន្ថែម Use នេះ
use App\Models\User;
use App\Models\Customer; // (អ្នកប្រហែលជាមាន Customer relationship ដែរ)
use App\Models\Orderdetails; // (អ្នកប្រហែលជាមាន Details relationship ដែរ)

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


    /**
     * ទំនាក់ទំនងសម្រាប់ទាញយក Order Details
     * (អ្នកប្រហែលជាមាន Function នេះហើយ)
     */
    public function orderDetails()
    {
        return $this->hasMany(Orderdetails::class, 'order_id');
    }


    /**
     * ⬇️ ⬇️ ⬇️ ជំហានទី២៖ បន្ថែម Function នេះ ⬇️ ⬇️ ⬇️
     * * ទំនាក់ទំនងសម្រាប់ទាញយក User (អ្នកលក់/Cashier)
     * វានឹងភ្ជាប់ 'user_id' នៅក្នុងតារាង 'orders' ទៅ 'id' នៅក្នុងតារាង 'users'
     */
    public function user()
    {
        // យើងសន្មត់ថា Foreign Key គឺ 'user_id'
        // បើ Foreign Key របស់អ្នកឈ្មោះផ្សេង សូមដាក់ក្នុង Parameter ទីពីរ
        // ឧទាហរណ៍: return $this->belongsTo(User::class, 'cashier_id');
        return $this->belongsTo(User::class, 'user_id');
    }
    

    
    
}
