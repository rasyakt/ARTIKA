<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('user')
            ->latest('date')
            ->paginate(20);

        $categories = ['Gaji', 'Sewa', 'Listrik/Air', 'Transportasi', 'Lainnya'];

        return view('admin.expenses.index', compact('expenses', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        Expense::create([
            'date' => $request->date,
            'category' => $request->category,
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
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $expense->update([
            'date' => $request->date,
            'category' => $request->category,
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
