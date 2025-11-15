<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_category_id',
        'user_id',
        'description',
        'amount',
        'expense_date',
        'notes',
        'receipt_image' // ត្រូវប្រយ័ត្នពេល upload file
    ];

    // ការចំណាយមួយ ស្ថិតនៅក្រោមប្រភេទតែមួយ
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    // ការចំណាយមួយ ត្រូវបានកត់ត្រាដោយ User ម្នាក់
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}