<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'payment_date',
        'month_year',
        'base_salary',
        'bonus',
        'deduction',
        'net_salary',
        'notes',
    ];

    // ការបើកប្រាក់ខែម្តង (One) ជារបស់បុគ្គលិកតែម្នាក់ (One)
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}