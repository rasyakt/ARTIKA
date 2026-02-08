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
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = $request->except('_token');

        // Handle checkboxes (if not present, they are false)
        $keys = ['enable_camera'];

        foreach ($keys as $key) {
            $value = isset($settings[$key]) ? '1' : '0';
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings')->with('success', __('admin.settings_updated') ?? 'Settings updated successfully.');
    }
}
