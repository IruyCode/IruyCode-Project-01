<?php

namespace App\Modules\BankManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountBalance extends Model
{
    use HasFactory;

    protected $table = 'app_bank_manager_account_balances';

    protected $fillable = ['balance', 'user_id'];
}
