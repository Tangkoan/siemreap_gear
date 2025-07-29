<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExchangeRate extends Model
{
    //
    use HasFactory;
    protected $table = 'exchange_rates';  // ត្រូវតាម migration
    
    protected $fillable = [
        'rate_khr',  // ✅ Corrected to match your database
        'rate_date', // ✅ Added to match your database
        'is_active',
    ];

}
