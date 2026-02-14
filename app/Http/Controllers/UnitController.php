<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::latest()->paginate(10);
        return view('admin.units.index', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:units,name',
            'short_name' => 'nullable|string|max:50',
        ]);

        Unit::create($request->all());

        return redirect()->back()->with('success', 'Unit created successfully!');
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:units,name,' . $unit->id,
            'short_name' => 'nullable|string|max:50',
        ]);

        $unit->update($request->all());

        return redirect()->back()->with('success', 'Unit updated successfully!');
    }

    public function destroy(Unit $unit)
    {
        // Optional: check if unit is used in products or pre-orders
        $unit->delete();
        return redirect()->back()->with('success', 'Unit deleted successfully!');
    }
}
