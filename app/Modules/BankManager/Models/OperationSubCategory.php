<?php

namespace App\Modules\BankManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationSubCategory extends Model
{
    use HasFactory;

    protected $table = 'app_bank_manager_operation_sub_categories';

    protected $fillable = ['operation_category_id', 'name'];

    public function operationCategory()
    {
        return $this->belongsTo(OperationCategory::class, 'operation_category_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'operation_sub_category_id');
    }

}