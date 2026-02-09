<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SuperadminController extends Controller
{
    /**
     * Display the developer tools dashboard.
     */
    public function index()
    {
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_os' => PHP_OS_FAMILY,
            'db_connection' => config('database.default'),
            'db_version' => $this->getDbVersion(),
            'server_time' => now()->toDateTimeString(),
            'is_maintenance' => app()->isDownForMaintenance(),
            'environment' => app()->environment(),
        ];

        $dbStats = $this->getDatabaseStats();

        return view('superadmin.dashboard', compact('systemInfo', 'dbStats'));
    }

    /**
     * Get list of tables and record counts.
     */
    private function getDatabaseStats()
    {
        $stats = [];
        try {
            $driver = config('database.default');
            $tables = [];

            if ($driver === 'mysql') {
                $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
            } elseif ($driver === 'pgsql') {
                $tables = \Illuminate\Support\Facades\DB::select("SELECT tablename as table_name FROM pg_catalog.pg_tables WHERE schemaname = 'public'");
            }

            foreach ($tables as $table) {
                // Get the first property value from the stdClass object (the table name)
                $tableArray = (array) $table;
                $tableName = reset($tableArray);

                if ($tableName) {
                    $count = \Illuminate\Support\Facades\DB::table($tableName)->count();
                    $stats[] = [
                        'name' => $tableName,
                        'count' => $count
                    ];
                }
            }
        } catch (\Exception $e) {
            // Fallback for non-MySQL or errors
        }
        return $stats;
    }

    /**
     * Toggle Maintenance Mode.
     */
    public function toggleMaintenance(Request $request)
    {
        if (app()->isDownForMaintenance()) {
            Artisan::call('up');
            return back()->with('success', 'Application is now LIVE.');
        } else {
            // Secret token to allow superadmin access while down
            $token = bin2hex(random_bytes(8));
            Artisan::call('down', [
                '--secret' => $token
            ]);

            // Log the secret link for the developer
            \Illuminate\Support\Facades\Log::info("Maintenance Mode enabled. Access via: " . url('/' . $token));

            return back()->with('success', 'Application is now in MAINTENANCE MODE. Secret token: ' . $token);
        }
    }

    /**
     * Get Database Version.
     */
    private function getDbVersion()
    {
        try {
            $results = \Illuminate\Support\Facades\DB::select('SELECT VERSION() as version');
            return $results[0]->version ?? 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    /**
     * Clear application cache.
     */
    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');

        return back()->with('success', 'System cache cleared successfully!');
    }

    /**
     * Run system optimization.
     */
    public function optimize()
    {
        Artisan::call('optimize');

        return back()->with('success', 'System optimized successfully!');
    }

    /**
     * Display system logs.
     */
    public function logs()
    {
        $logPath = storage_path('logs/laravel.log');
        $logs = '';

        if (File::exists($logPath)) {
            // Get last 500 lines
            $file = new \SplFileObject($logPath, 'r');
            $file->seek(PHP_INT_MAX);
            $lastLine = $file->key();
            $lines = new \LimitIterator($file, max(0, $lastLine - 500), $lastLine);

            foreach ($lines as $line) {
                $logs .= $line;
            }
        } else {
            $logs = 'Log file not found.';
        }

        return view('superadmin.logs', compact('logs'));
    }

    /**
     * Display advanced system settings.
     */
    public function settings()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key');

        // Define default settings categories
        $categories = [
            'General' => [
                'system_name' => ['label' => 'System Name', 'type' => 'text', 'default' => 'ARTIKA POS'],
                'footer_text' => ['label' => 'Footer Text', 'type' => 'text', 'default' => '© ' . date('Y') . ' RPL_Sentinel. All rights reserved.'],
            ],
            'Admin Features' => [
                'admin_enable_audit_logs' => ['label' => 'Audit Logs', 'type' => 'boolean', 'default' => true],
                'admin_enable_reports' => ['label' => 'Detailed Reports', 'type' => 'boolean', 'default' => true],
                'admin_enable_camera' => ['label' => 'Feature Toggles (Scanner)', 'type' => 'boolean', 'default' => true],
                'admin_enable_promos' => ['label' => 'Promos & Discounts Management', 'type' => 'boolean', 'default' => true],
            ],
            'Warehouse Features' => [
                'warehouse_enable_adjust' => ['label' => 'Stock Adjustment', 'type' => 'boolean', 'default' => true],
                'warehouse_enable_scrap' => ['label' => 'Scrap/Delete Batches', 'type' => 'boolean', 'default' => true],
            ],
            'Cashier Features' => [
                'cashier_enable_returns' => ['label' => 'Returns & Refunds', 'type' => 'boolean', 'default' => true],
                'cashier_enable_discounts' => ['label' => 'Manual Discounts', 'type' => 'boolean', 'default' => true],
                'cashier_enable_camera' => ['label' => 'Camera Scanner', 'type' => 'boolean', 'default' => true],
                'cashier_enable_audit_logs' => ['label' => 'Activity Logs', 'type' => 'boolean', 'default' => true],
            ],
            'Sustainability & Performance' => [
                'session_duration' => ['label' => 'Session Duration (Minutes)', 'type' => 'number', 'default' => 120],
                'auto_optimize' => ['label' => 'Auto-Optimize on Login', 'type' => 'boolean', 'default' => false],
            ],
        ];

        return view('superadmin.settings.index', compact('settings', 'categories'));
    }

    /**
     * Update system settings.
     */
    public function updateSettings(Request $request)
    {
        $settings = $request->except('_token');

        foreach ($settings as $key => $value) {
            // Convert 'on' from checkboxes to boolean strings
            if ($value === 'on')
                $value = 'true';
            \App\Models\Setting::set($key, $value);
        }

        // Handle unchecked checkboxes (they won't be in the request)
        $allKeys = [
            'admin_enable_audit_logs',
            'admin_enable_reports',
            'admin_enable_camera',
            'admin_enable_promos',
            'warehouse_enable_adjust',
            'warehouse_enable_scrap',
            'cashier_enable_returns',
            'cashier_enable_discounts',
            'cashier_enable_camera',
            'cashier_enable_audit_logs',
            'auto_optimize'
        ];

        foreach ($allKeys as $key) {
            if (!$request->has($key)) {
                \App\Models\Setting::set($key, 'false');
            }
        }

        return back()->with('success', 'System settings updated successfully!');
    }
}
