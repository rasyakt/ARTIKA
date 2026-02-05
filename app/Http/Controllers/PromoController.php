<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promos = Promo::with(['product', 'category'])->latest()->paginate(10);
        $products = Product::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.promos.index', compact('promos', 'products', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'product_id' => 'nullable|exists:products,id',
            'category_id' => 'nullable|exists:categories,id',
            'min_purchase' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        Promo::create($validated);

        return redirect()->route('admin.promos.index')
            ->with('success', __('admin.promo_added_success'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $promo = Promo::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'product_id' => 'nullable|exists:products,id',
            'category_id' => 'nullable|exists:categories,id',
            'min_purchase' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $promo->update($validated);

        return redirect()->route('admin.promos.index')
            ->with('success', __('admin.promo_updated_success'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $promo = Promo::findOrFail($id);
        $promo->delete();

        return redirect()->route('admin.promos.index')
            ->with('success', __('admin.promo_deleted_success'));
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus($id)
    {
        $promo = Promo::findOrFail($id);
        $promo->is_active = !$promo->is_active;
        $promo->save();

        return response()->json(['success' => true, 'is_active' => $promo->is_active]);
    }
}
