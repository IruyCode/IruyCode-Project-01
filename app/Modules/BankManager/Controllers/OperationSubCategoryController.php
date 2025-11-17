<?php

namespace App\Modules\BankManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\BankManager\Models\OperationSubCategory;
use App\Modules\BankManager\Models\Transaction;

class OperationSubCategoryController extends Controller
{
    public function storeOperationSubCategory(Request $request)
    {
        $request->validate([
            'operation_category_id' => 'required|exists:app_bank_manager_operation_categories,id',
            'name' => 'required|string|max:255',
        ]);

        OperationSubCategory::create([
            'operation_category_id' => $request->operation_category_id,
            'name' => $request->name,
        ]);

        return back()->with('success', 'Subcategoria criada com sucesso!');
    }

    public function updateSubCategory(Request $request, $id)
    {
        $request->validate([
            'operation_category_id' => 'required|exists:app_bank_manager_operation_categories,id',
            'name' => 'required|string|max:255',
        ]);

        $sub = OperationSubCategory::findOrFail($id);
        $sub->operation_category_id = $request->operation_category_id;
        $sub->name = $request->name;
        $sub->save();

        return back()->with('success', 'Subcategoria atualizada com sucesso!');
    }

    public function deleteSubCategory($id)
    {
        $sub = OperationSubCategory::findOrFail($id);

        $hasTransactions = Transaction::where('operation_sub_category_id', $id)->exists();

        if ($hasTransactions) {
            return back()->with('error', 'Não é possível apagar: existem transações usando esta subcategoria.');
        }

        $sub->delete();

        return back()->with('success', 'Subcategoria apagada com sucesso.');
    }
}
