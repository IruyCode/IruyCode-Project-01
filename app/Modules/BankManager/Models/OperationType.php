<?php

namespace App\Modules\BankManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationType extends Model
{
    use HasFactory;

    protected $table = 'app_bank_manager_operation_types';

    protected $fillable = [
        'operation_type',
        'description',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'operation_type_id');
    }
}
