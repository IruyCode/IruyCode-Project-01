<?php

namespace App\Modules\BankManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationCategory extends Model
{
    use HasFactory;

    protected $table = 'app_bank_manager_operation_categories';

    protected $fillable = ['operation_type_id', 'name'];

    public function operationType()
    {
        return $this->belongsTo(OperationType::class, 'operation_type_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'operation_category_id');
    }
}
