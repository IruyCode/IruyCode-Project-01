<?php

namespace App\Modules\BankManager\Models\Investments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'app_bank_manager_investments';

    protected $fillable = [
        'name',
        'platform',
        'type',
        'initial_amount',
        'current_amount',
        'start_date',
        'monthly_profit',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'initial_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'monthly_profit' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (is_null($model->current_amount)) {
                $model->current_amount = $model->initial_amount;
            }

            if (is_null($model->start_date)) {
                $model->start_date = now()->toDateString();
            }
        });
    }

    /**
     * Get the investment's historical records.
     */
    public function histories()
    {
        return $this->hasMany(InvestmentHistory::class, 'investment_id');
    }

    public function transactions()
    {
        return $this->hasMany(InvestmentHistoryTransaction::class, 'investment_id');
    }
}