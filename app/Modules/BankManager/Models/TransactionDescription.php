<?php

namespace App\Modules\BankManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDescription extends Model
{
    use HasFactory;

    protected $table = 'app_bank_manager_transaction_descriptions';
    
    protected $fillable = ['transaction_id', 'description'];

    // Relacionamento reverso
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
