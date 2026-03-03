<?php

namespace App\Http\Controllers;

use App\Models\IdentityType;
use Illuminate\Http\Request;

class IdentityTypeController extends Controller
{
    /**
     * Display a listing of the identity types.
     */
    public function index()
    {
        $types = IdentityType::latest()->get();
        return view('admin.identity-types.index', compact('types'));
    }

    /**
     * Store a newly created identity type in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:identity_types',
            'label' => 'required|string|max:255',
        ]);

        IdentityType::create([
            'name' => strtolower($request->name),
            'label' => $request->label,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Identity Type created successfully!');
    }

    /**
     * Update the specified identity type in storage.
     */
    public function update(Request $request, $id)
    {
        $type = IdentityType::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:identity_types,name,' . $id,
            'label' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $type->update([
            'name' => strtolower($request->name),
            'label' => $request->label,
            'is_active' => $request->has('is_active') ? $request->is_active : $type->is_active,
        ]);

        return redirect()->back()->with('success', 'Identity Type updated successfully!');
    }

    /**
     * Remove the specified identity type from storage.
     */
    public function destroy($id)
    {
        $type = IdentityType::findOrFail($id);

        // Optional: check if users are using this type before deleting
        if ($type->users()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete Identity Type that is in use by users!');
        }

        $type->delete();

        return redirect()->back()->with('success', 'Identity Type deleted successfully!');
    }
}
