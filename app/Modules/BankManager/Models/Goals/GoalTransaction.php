<?php

namespace App\Modules\BankManager\Models\Goals;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoalTransaction extends Model
{
    use HasFactory;

    protected $table = 'app_bank_manager_goal_transactions';

    protected $fillable = ['goal_id', 'type', 'amount', 'note', 'performed_at'];

    protected $casts = [
        'performed_at' => 'datetime',
    ];

    public function goal()
    {
        return $this->belongsTo(FinancialGoals::class, 'goal_id');
    }
}
