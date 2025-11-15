<?php

namespace App\Modules\BankManager\Models\Debts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use HasFactory;

    protected $table = 'app_bank_manager_debts';

    protected $fillable = [
        'name',
        'description',
        'total_amount',
        'installments',
        'start_date',
    ];

    // No model AppBankManagerDebt:
    public function installmentsList()
    {
        return $this->hasMany(DebtInstallment::class, 'debt_id');
    }


    public function paidInstallments()
    {
        return $this->installments()->whereNotNull('paid_at');
    }

    public function remainingInstallments()
    {
        return $this->installments()->whereNull('paid_at');
    }
}
