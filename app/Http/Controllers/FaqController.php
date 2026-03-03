<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    /**
     * Public FAQ page — visible to all authenticated users.
     * Shows FAQs relevant to the user's role.
     */
    public function index()
    {
        if (!\App\Models\Setting::get('enable_faq', true)) {
            $user = Auth::user();
            $route = $user && strtolower($user->role->name ?? '') === 'cashier' ? 'pos.index' : 'dashboard';
            return redirect()->route($route)->with('error', 'Fitur Pusat Bantuan saat ini sedang dinonaktifkan oleh administrator.');
        }

        $user = Auth::user();
        $role = strtolower($user->role->name ?? 'user');

        $faqs = Faq::active()
            ->forRole($role)
            ->ordered()
            ->get()
            ->groupBy('category');

        $categories = array_intersect_key(Faq::CATEGORIES, $faqs->toArray());

        return view('faq.index', compact('faqs', 'categories', 'role'));
    }

    /**
     * Superadmin FAQ management page.
     */
    public function manage()
    {
        $faqs = Faq::ordered()->get();
        $categories = Faq::CATEGORIES;
        $targetRoles = Faq::TARGET_ROLES;

        return view('superadmin.faq', compact('faqs', 'categories', 'targetRoles'));
    }

    /**
     * Store a new FAQ (superadmin only).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'category' => 'required|string|in:' . implode(',', array_keys(Faq::CATEGORIES)),
            'target_role' => 'nullable|string|in:cashier,admin,manager,warehouse,superadmin',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->has('is_active') ? true : false;

        Faq::create($validated);

        return redirect()->route('superadmin.faq')
            ->with('success', 'FAQ berhasil ditambahkan!');
    }

    /**
     * Update an existing FAQ (superadmin only).
     */
    public function update(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);

        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'category' => 'required|string|in:' . implode(',', array_keys(Faq::CATEGORIES)),
            'target_role' => 'nullable|string|in:cashier,admin,manager,warehouse,superadmin',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['target_role'] = $request->input('target_role') ?: null;

        $faq->update($validated);

        return redirect()->route('superadmin.faq')
            ->with('success', 'FAQ berhasil diperbarui!');
    }

    /**
     * Delete a FAQ (superadmin only).
     */
    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return redirect()->route('superadmin.faq')
            ->with('success', 'FAQ berhasil dihapus!');
    }
}
