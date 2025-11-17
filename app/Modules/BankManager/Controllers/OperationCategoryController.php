<?php

namespace App\Modules\BankManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\BankManager\Models\OperationCategory;
use App\Modules\BankManager\Models\Transaction;
use App\Modules\BankManager\Models\OperationSubCategory;

class OperationCategoryController extends Controller
{
    public function storeOperationCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        OperationCategory::create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Categoria criada com sucesso!');
    }


    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = OperationCategory::findOrFail($id);
        $category->name = $request->name;
        $category->save();

        return back()->with('success', 'Categoria atualizada com sucesso!');
    }

    public function deleteCategory($id)
    {
        $category = OperationCategory::findOrFail($id);

        // verificar se existe transação usando subcategorias desta categoria
        $hasTransactions = Transaction::whereHas('operationSubCategory', function ($q) use ($id) {
            $q->where('operation_category_id', $id);
        })->exists();

        if ($hasTransactions) {
            return back()->with('error', 'Não é possível apagar: existem transações usando esta categoria.');
        }

        // delete subcategorias
        OperationSubCategory::where('operation_category_id', $id)->delete();

        // delete categoria
        $category->delete();

        return back()->with('success', 'Categoria apagada com sucesso.');
    }
}
