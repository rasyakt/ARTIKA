<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = [
            'enable_camera' => Setting::get('enable_camera', true),
            'receipt_paper_size' => Setting::get('receipt_paper_size', '58mm'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = $request->except('_token');

        // Handle checkboxes (if not present, they are false)
        $checkboxKeys = ['enable_camera'];
        foreach ($checkboxKeys as $key) {
            $value = isset($settings[$key]) ? '1' : '0';
            Setting::set($key, $value);
        }

        // Handle select/text settings
        $selectKeys = ['receipt_paper_size'];
        foreach ($selectKeys as $key) {
            if (isset($settings[$key])) {
                Setting::set($key, $settings[$key]);
            }
        }

        return redirect()->route('admin.settings')->with('success', __('admin.settings_updated') ?? 'Settings updated successfully.');
    }
}
