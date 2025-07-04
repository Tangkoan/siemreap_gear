<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WareHouse extends Model
{
    //
    protected $table = 'ware_houses';  // ត្រូវតាម migration
    protected $guarded = [];

    
    // ករណីចង Relationship ដោយមិនចង់អោយគេលុបWarehoueនេះទៅ ព្រោះមានទំនាក់ទំនងជាមួយ Product
    public function products()
    {
        return $this->hasMany(Product::class, 'warehouse_id');
    }


}
