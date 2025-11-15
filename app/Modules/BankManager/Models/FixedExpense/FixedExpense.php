<?php

namespace App\Modules\BankManager\Models\FixedExpense;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Modules\BankManager\Models\OperationCategory;

class FixedExpense extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'amount', 'due_day', 'operation_category_id', 'last_generated_at'];

    public function category()
    {
        return $this->belongsTo(OperationCategory::class, 'operation_category_id');
    }
    public function payments()
    {
        return $this->hasMany(FixedExpensePayment::class, 'fixed_expense_id');
    }

    /**
     * Retorna o status da despesa para um ano/mês específico.
     *
     * @param int $year
     * @param int $month
     * @return string ('paga', 'atrasada', 'em aberto')
     */
    public function getStatusForMonth(int $year, int $month): string
    {
        // Já existe pagamento registrado?
        $payment = $this->payments()->whereYear('paid_at', $year)->whereMonth('paid_at', $month)->first();

        if ($payment) {
            return 'paga';
        }

        // Data de vencimento
        $dueDate = \Carbon\Carbon::create($year, $month, $this->due_day, 0, 0, 0);

        if (now()->greaterThan($dueDate)) {
            return 'atrasada';
        }

        return 'em aberto';
    }
}
