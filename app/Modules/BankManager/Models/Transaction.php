<?php

namespace App\Modules\BankManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'app_bank_manager_transactions';

    protected $fillable = ['operation_category_id', 'amount', 'account_balance_id'];

    public function operationCategory()
    {
        return $this->belongsTo(OperationCategory::class, 'operation_category_id');
    }

    public function accountBalance()
    {
        return $this->belongsTo(AccountBalance::class, 'account_balance_id');
    }
}
