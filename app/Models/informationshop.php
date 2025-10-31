<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class informationshop extends Model
{
    // ការពារ `id` មិនឱ្យបំពេញដោយអ្នកប្រើ ហើយអនុញ្ញាតឱ្យបំពេញ Fields ផ្សេងទៀត
    protected $fillable = [
        'name_kh',
        'name_en',
        'address',
        'phone',
        'logo', // <--- សូមប្រាកដថា 'logo' មាននៅក្នុងនេះ
        'favicon',
        'note',
        'terms_and_condition',
    ];

}
