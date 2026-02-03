<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::withCount('expenses')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.expense_categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories',
            'description' => 'nullable|string|max:500',
        ]);

        ExpenseCategory::create($request->all());

        return redirect()->route('admin.expense-categories.index')
            ->with('success', __('admin.category_created_success'));
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name,' . $expenseCategory->id,
            'description' => 'nullable|string|max:500',
        ]);

        $expenseCategory->update($request->all());

        return redirect()->route('admin.expense-categories.index')
            ->with('success', __('admin.category_updated_success'));
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        if ($expenseCategory->expenses()->count() > 0) {
            return redirect()->route('admin.expense-categories.index')
                ->with('error', __('admin.category_has_expenses_error'));
        }

        $expenseCategory->delete();

        return redirect()->route('admin.expense-categories.index')
            ->with('success', __('admin.category_deleted_success'));
    }
}
