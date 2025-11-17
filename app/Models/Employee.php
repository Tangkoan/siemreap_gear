<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    // អនុញ្ញាតឲ្យ Mass Assign (បញ្ចូលទិន្នន័យ) លើ field ទាំងនេះ
    protected $fillable = [
        'name',
        'phone',
        'position',
        'base_salary',
        'join_date',
        'status',
    ];

    // បុគ្គលិកម្នាក់ (One) មានការបើកប្រាក់ខែច្រើនដង (Many)
    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
}