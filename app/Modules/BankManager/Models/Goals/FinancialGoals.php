<?php

namespace App\Modules\BankManager\Models\Goals;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialGoals extends Model
{
    use HasFactory;

    protected $table = 'app_bank_manager_financial_goals';

    protected $fillable = ['name', 'description', 'target_amount', 'current_amount', 'deadline', 'is_completed', 'completed_at'];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
        'deadline' => 'date',
    ];

    public function transactions()
    {
        return $this->hasMany(GoalTransaction::class, 'goal_id');
    }
}
