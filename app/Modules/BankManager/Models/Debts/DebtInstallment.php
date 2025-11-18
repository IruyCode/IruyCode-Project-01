<?php

namespace App\Modules\BankManager\Models\Debts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtInstallment extends Model
{
    use HasFactory;

    protected $table = 'app_bank_manager_debt_installments';

    protected $fillable = [
        'debt_id',
        'installment_number',
        'amount',
        'due_date',
        'paid_at',
    ];

    protected $dates = [
        'due_date',
        'paid_at',
    ];

    public function debt()
    {
        return $this->belongsTo(Debt::class, 'debt_id');
    }

    public function isPaid()
    {
        return !is_null($this->paid_at);
    }


}
