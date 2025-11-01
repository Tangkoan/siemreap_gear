<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    //
     protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * ✅ START: បន្ថែម Function ថ្មីនេះ
     * យក Order ទាំងអស់ដែលបានកើតឡើងក្នុងអំឡុងពេលវេននេះ
     */
    public function orders()
    {
        // វេន (Shift) មួយ មាន Order ច្រើន (hasMany)
        return $this->hasMany(Order::class, 'shift_id');
    }
    /**
     * ✅ END: បញ្ចប់ Function ថ្មី
     */
}
