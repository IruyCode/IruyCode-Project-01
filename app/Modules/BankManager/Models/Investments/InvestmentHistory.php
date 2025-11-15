<?php

namespace App\Modules\BankManager\Models\Investments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentHistory extends Model
{
    use HasFactory;

    protected $table = 'app_bank_manager_investment_history';

    protected $fillable = [
        'investment_id',
        'reference_date',
        'amount',
        'variation',
        'percentage',
    ];

    protected $casts = [
        'reference_date' => 'date',
        'amount' => 'decimal:2',
        'variation' => 'decimal:2',
        'percentage' => 'decimal:2',
    ];

    /**
     * Cada registro de histórico pertence a um investimento.
     */
    public function investment()
    {
        return $this->belongsTo(Investment::class, 'investment_id');
    }
}
