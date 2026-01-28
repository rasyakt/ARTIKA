<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\TransactionItem;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|unique:suppliers',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
        ]);

        Supplier::create($request->all());

        return redirect()->route('admin.suppliers')->with('success', 'Supplier created successfully!');
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|unique:suppliers,phone,' . $id,
            'email' => 'nullable|email',
            'address' => 'nullable|string',
        ]);

        $supplier->update($request->all());

        return redirect()->route('admin.suppliers')->with('success', 'Supplier updated successfully!');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('admin.suppliers')->with('success', 'Supplier deleted successfully!');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['purchases.product', 'purchases.user']);
        $purchases = $supplier->purchases()->latest()->paginate(10);
        $products = Product::all();

        // Get sales performance and current stock for products supplied by this supplier
        $productIds = $supplier->purchases()->distinct()->pluck('product_id');
        $salesPerformance = TransactionItem::whereIn('product_id', $productIds)
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_id')
            ->with(['product.stock'])
            ->get();

        return view('admin.suppliers.show', compact('supplier', 'purchases', 'products', 'salesPerformance'));
    }

    public function exportPdf(Supplier $supplier)
    {
        $supplier->load(['purchases.product', 'purchases.user']);

        // Get sales performance and current stock for products supplied by this supplier
        $productIds = $supplier->purchases()->distinct()->pluck('product_id');
        $salesPerformance = TransactionItem::whereIn('product_id', $productIds)
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_id')
            ->with(['product.stock'])
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.suppliers.report', [
            'supplier' => $supplier,
            'purchases' => $supplier->purchases()->orderBy('purchase_date', 'desc')->get(),
            'salesPerformance' => $salesPerformance
        ]);

        return $pdf->download('Supplier_Report_' . $supplier->name . '_' . date('Ymd') . '.pdf');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        $suppliers = Supplier::where('name', 'ilike', "%{$query}%")
            ->orWhere('phone', 'ilike', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json($suppliers);
    }
}
