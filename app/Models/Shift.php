<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shift extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'user_id',
        'opening_cash_usd', // ✅ NEW
        'opening_cash_khr', // ✅ NEW
        'exchange_rate',    // ✅ NEW
        'opened_at',
        'closed_at',
        'closing_cash_usd', // ✅ NEW
        'closing_cash_khr', // ✅ NEW
        'notes',
        'status',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
