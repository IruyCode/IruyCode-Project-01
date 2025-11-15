<?php

namespace App\Modules\BankManager\Models\Investments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentHistoryTransaction extends Model
{
    use HasFactory;

    protected $table = 'app_bank_manager_investment_history_transactions';

    protected $fillable = [
        'investment_id',
        'type',
        'amount',
        'description',
        'performed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'performed_at' => 'datetime',
    ];

    public function investment()
    {
        return $this->belongsTo(Investment::class, 'investment_id');
    }
}
