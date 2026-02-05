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

    public function exportCsv(Supplier $supplier)
    {
        $supplier->load(['purchases.product', 'purchases.user']);

        // Get sales performance and current stock for products supplied by this supplier
        $productIds = $supplier->purchases()->distinct()->pluck('product_id');
        $salesPerformance = TransactionItem::whereIn('product_id', $productIds)
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_id')
            ->with(['product.stock'])
            ->get();

        $filename = 'Supplier_Report_' . $supplier->name . '_' . date('Ymd') . '.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($supplier, $salesPerformance) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            $delimiter = ';';

            fputcsv($file, [__('admin.supplier_report') ?? 'SUPPLIER REPORT', $supplier->name], $delimiter);
            fputcsv($file, [__('admin.phone') . ':', $supplier->phone], $delimiter);
            fputcsv($file, [__('admin.email') . ':', $supplier->email], $delimiter);
            fputcsv($file, [], $delimiter);

            // Sales Performance
            if ($salesPerformance->count() > 0) {
                fputcsv($file, [strtoupper(__('admin.sales_performance_inventory') ?? 'SALES PERFORMANCE & INVENTORY')], $delimiter);
                fputcsv($file, [__('admin.product_name'), __('admin.barcode'), __('admin.sold_count') ?? 'Sold Count', __('admin.total_revenue') ?? 'Total Revenue', __('admin.current_stock')], $delimiter);
                foreach ($salesPerformance as $item) {
                    fputcsv($file, [
                        $item->product->name,
                        $item->product->barcode,
                        $item->total_sold,
                        'Rp ' . number_format($item->total_revenue, 0, ',', '.'),
                        $item->product->stock->quantity ?? 0
                    ], $delimiter);
                }
                fputcsv($file, [], $delimiter);
            }

            // Purchase History
            $purchases = $supplier->purchases()->orderBy('purchase_date', 'desc')->get();
            if ($purchases->count() > 0) {
                fputcsv($file, [strtoupper(__('admin.purchase_history') ?? 'PURCHASE HISTORY')], $delimiter);
                fputcsv($file, [__('admin.date'), __('admin.invoice'), __('admin.product'), __('admin.quantity'), __('admin.cost'), __('admin.total_cost')], $delimiter);
                foreach ($purchases as $p) {
                    fputcsv($file, [
                        $p->purchase_date->format('Y-m-d'),
                        $p->invoice_no,
                        $p->product->name,
                        $p->quantity,
                        'Rp ' . number_format($p->cost_price, 0, ',', '.'),
                        'Rp ' . number_format($p->total_cost, 0, ',', '.')
                    ], $delimiter);
                }
                fputcsv($file, [], $delimiter);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
