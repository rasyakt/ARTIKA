<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\AuditLog;
use App\Models\Journal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function show(Request $request, $id)
    {
        $transaction = Transaction::with(['items.product', 'user'])->findOrFail($id);

        if ($request->input('format') === 'pdf') {
            $pdf = Pdf::loadView('admin.transactions.print', [
                'transaction' => $transaction,
                'isPdf' => true
            ]);
            return $pdf->download('transaction-' . $transaction->invoice_no . '.pdf');
        }

        return view('admin.transactions.show', compact('transaction'));
    }

    public function edit($id)
    {
        $transaction = Transaction::with(['items.product'])->findOrFail($id);
        return view('admin.transactions.edit', compact('transaction'));
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::with('items')->findOrFail($id);

        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:transaction_items,id',
            'items.*.quantity' => 'required|integer|min:0',
        ]);

        try {
            DB::transaction(function () use ($transaction, $request) {
                $newTotal = 0;

                foreach ($request->items as $itemData) {
                    $item = $transaction->items->firstWhere('id', $itemData['id']);
                    if (!$item)
                        continue;

                    $oldQty = $item->quantity;
                    $newQty = $itemData['quantity'];

                    if ($oldQty != $newQty) {
                        $diff = $newQty - $oldQty;
                        $stock = Stock::where('product_id', $item->product_id)->first();

                        if ($diff > 0) {
                            // Quantity increased, deduct more from stock
                            if ($stock && $stock->quantity < $diff) {
                                throw new \Exception('Stok tidak mencukupi untuk penambahan jumlah barang ' . $item->product->name);
                            }
                            if ($stock)
                                $stock->decrement('quantity', $diff);
                        } else {
                            // Quantity decreased, return to stock
                            $absDiff = abs($diff);
                            if ($stock) {
                                $stock->increment('quantity', $absDiff);
                            } else {
                                Stock::create(['product_id' => $item->product_id, 'quantity' => $absDiff]);
                            }
                        }

                        // Update Transaction Item
                        $item->quantity = $newQty;
                        $item->subtotal = $newQty * $item->price;
                        $item->save();

                        // Log Stock Movement
                        StockMovement::create([
                            'product_id' => $item->product_id,
                            'user_id' => Auth::id(),
                            'type' => $diff > 0 ? 'out' : 'in',
                            'quantity_before' => $stock ? ($diff > 0 ? $stock->quantity + $diff : $stock->quantity - $absDiff) : 0,
                            'quantity_after' => $stock ? $stock->quantity : $absDiff,
                            'quantity_change' => -$diff,
                            'reason' => 'Transaction Edit Adjustment',
                            'reference' => 'EDIT-' . $transaction->invoice_no
                        ]);
                    }

                    $newTotal += $item->subtotal;
                }

                // Update Transaction Header
                $transaction->total_amount = $newTotal;
                $transaction->save();

                // Update Journals
                Journal::where('transaction_id', $transaction->id)->update(['amount' => $newTotal]);

                // Audit Log
                AuditLog::log(
                    'transaction_updated',
                    'Transaction',
                    $transaction->id,
                    $newTotal,
                    $transaction->payment_method,
                    [
                        'invoice_no' => $transaction->invoice_no,
                        'updated_by' => Auth::user()->name,
                        'old_total' => $transaction->getOriginal('total_amount')
                    ],
                    'Updated Transaction: ' . $transaction->invoice_no
                );
            });

            return redirect()->route('admin.dashboard')->with('success', 'Transaksi berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui transaksi: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);

        try {
            DB::transaction(function () use ($transaction) {
                // If the transaction is NOT already rolled back or canceled, we should restore stock
                if ($transaction->status !== 'rolled_back' && $transaction->status !== 'canceled') {
                    foreach ($transaction->items as $item) {
                        $stock = Stock::where('product_id', $item->product_id)->first();
                        if ($stock) {
                            $stock->increment('quantity', $item->quantity);
                        } else {
                            Stock::create(['product_id' => $item->product_id, 'quantity' => $item->quantity]);
                        }
                    }
                }

                Journal::where('transaction_id', $transaction->id)->delete();
                $transaction->delete(); // This will cascade delete items if DB is set up that way, or we delete manually

                AuditLog::log(
                    'transaction_deleted',
                    'Transaction',
                    $transaction->id,
                    $transaction->total_amount,
                    $transaction->payment_method,
                    ['invoice_no' => $transaction->invoice_no],
                    'Deleted Transaction: ' . $transaction->invoice_no
                );
            });

            return redirect()->back()->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    public function rollback($id)
    {
        $transaction = Transaction::with('items')->findOrFail($id);

        if ($transaction->status === 'rolled_back') {
            return redirect()->back()->with('error', 'Transaksi ini sudah di-rollback sebelumnya.');
        }

        try {
            DB::transaction(function () use ($transaction) {
                // ... same logic as before ...
                foreach ($transaction->items as $item) {
                    $stock = Stock::where('product_id', $item->product_id)->first();
                    $qtyBefore = $stock ? $stock->quantity : 0;

                    if ($stock) {
                        $stock->increment('quantity', $item->quantity);
                    } else {
                        Stock::create([
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity
                        ]);
                    }

                    $newStock = Stock::where('product_id', $item->product_id)->first();
                    $qtyAfter = $newStock ? $newStock->quantity : 0;

                    StockMovement::create([
                        'product_id' => $item->product_id,
                        'user_id' => Auth::id(),
                        'type' => 'in',
                        'quantity_before' => $qtyBefore,
                        'quantity_after' => $qtyAfter,
                        'quantity_change' => $item->quantity,
                        'reason' => 'Transaction Rollback',
                        'reference' => 'ROLLBACK-' . $transaction->invoice_no
                    ]);
                }

                Journal::where('transaction_id', $transaction->id)->delete();
                $transaction->update(['status' => 'rolled_back']);

                AuditLog::log(
                    'transaction_rollback',
                    'Transaction',
                    $transaction->id,
                    $transaction->total_amount,
                    $transaction->payment_method,
                    ['invoice_no' => $transaction->invoice_no, 'rolled_back_by' => Auth::user()->name],
                    'Rolled back Invoice: ' . $transaction->invoice_no
                );
            });

            return redirect()->back()->with('success', 'Transaksi berhasil di-rollback.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal rollback: ' . $e->getMessage());
        }
    }
}
