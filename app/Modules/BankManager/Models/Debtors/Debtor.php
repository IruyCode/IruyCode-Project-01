<?php

namespace App\Modules\BankManager\Models\Debtors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debtor extends Model
{
    use HasFactory;

    protected $table = 'app_bank_manager_debtors';

    protected $fillable = ['name', 'description', 'amount', 'due_date', 'is_paid', 'paid_at'];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'is_paid' => 'boolean',
    ];

    public function edits()
    {
        return $this->hasMany(DebtorEdit::class, 'debtor_id');
    }

    // Quando um devedor é marcado como pago, remove o histórico de edições
    protected static function booted()
    {
        static::updated(function ($debtor) {
            if ($debtor->is_paid) {
                DebtorEdit::where('debtor_id', $debtor->id)->delete();
            }
        });
    }
}
