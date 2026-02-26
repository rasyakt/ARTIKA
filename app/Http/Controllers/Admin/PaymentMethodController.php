<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::ordered()->get();
        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.payment-methods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'icon' => 'nullable|string|max:50',
            'proof_requirement' => 'required|in:disabled,optional,required',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Ensure slug is unique
        $count = PaymentMethod::where('slug', 'like', $validated['slug'] . '%')->count();
        if ($count > 0) {
            $validated['slug'] = $validated['slug'] . '-' . ($count + 1);
        }

        PaymentMethod::create($validated);

        return redirect()->route('superadmin.payment-methods.index')
            ->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'icon' => 'nullable|string|max:50',
            'proof_requirement' => 'required|in:disabled,optional,required',
        ]);

        // Only update slug if name changed
        if ($paymentMethod->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
            $count = PaymentMethod::where('slug', 'like', $validated['slug'] . '%')
                ->where('id', '!=', $paymentMethod->id)
                ->count();
            if ($count > 0) {
                $validated['slug'] = $validated['slug'] . '-' . ($count + 1);
            }
        }

        $paymentMethod->update($validated);

        return redirect()->route('superadmin.payment-methods.index')
            ->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        // Check if the payment method is in use (Transactions)
        $useCount = \App\Models\Transaction::where('payment_method', $paymentMethod->slug)->count();

        if ($useCount > 0) {
            return redirect()->route('superadmin.payment-methods.index')
                ->with('error', 'Metode pembayaran tidak dapat dihapus karena sudah ada transaksi yang menggunakannya. Silakan nonaktifkan saja.');
        }

        $paymentMethod->delete();

        return redirect()->route('superadmin.payment-methods.index')
            ->with('success', 'Metode pembayaran berhasil dihapus.');
    }

    /**
     * Reorder payment methods via drag-and-drop.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:payment_methods,id',
        ]);

        foreach ($request->order as $index => $id) {
            PaymentMethod::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
