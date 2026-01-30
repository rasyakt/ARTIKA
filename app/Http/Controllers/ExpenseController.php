<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['user', 'category'])
            ->latest('date')
            ->paginate(20);

        $categories = \App\Models\ExpenseCategory::all();

        return view('admin.expenses.index', compact('expenses', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        Expense::create([
            'date' => $request->date,
            'expense_category_id' => $request->expense_category_id,
            'amount' => $request->amount,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.expenses.index')
            ->with('success', __('admin.expense_added_success'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'date' => 'required|date',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $expense->update([
            'date' => $request->date,
            'expense_category_id' => $request->expense_category_id,
            'amount' => $request->amount,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.expenses.index')
            ->with('success', __('admin.expense_updated_success'));
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('admin.expenses.index')
            ->with('success', __('admin.expense_deleted_success'));
    }
}
