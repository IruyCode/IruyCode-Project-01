<?php

namespace App\Modules\BankManager\Models\FixedExpense;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixedExpensePayment extends Model
{
    protected $fillable = ['fixed_expense_id', 'paid_at', 'year', 'month'];

    public function fixedExpense()
    {
        return $this->belongsTo(FixedExpense::class, 'fixed_expense_id');
    }
}
