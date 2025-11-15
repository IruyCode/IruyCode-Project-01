<?php

namespace App\Modules\BankManager\Models\Debtors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtorEdit extends Model
{
   use HasFactory;

    protected $table = 'app_bank_manager_debtor_edits';

    protected $fillable = [
        'debtor_id',
        'old_amount',
        'new_amount',
        'old_due_date',
        'new_due_date',
        'reason'
    ];

    protected $casts = [
        'old_due_date' => 'date',
        'new_due_date' => 'date',
    ];

    public function debtor()
    {
        return $this->belongsTo(Debtor::class, 'debtor_id');
    }
}
