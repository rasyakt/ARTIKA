<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

// 1. Restore 'admin' user to 'admin' role (ID 1)
$admin = User::where('username', 'admin')->first();
if ($admin) {
    $admin->role_id = 1;
    $admin->save();
    echo "Admin role restored for user 'admin'.\n";
} else {
    echo "User 'admin' not found.\n";
}

// 2. Ensure 'superadmin' user exists with role_id 6
if (!User::where('username', 'superadmin')->exists()) {
    User::create([
        'name' => 'Super Administrator',
        'username' => 'superadmin',
        'password' => Hash::make('superadmin123'),
        'role_id' => 6
    ]);
    echo "Superadmin account created (superadmin/superadmin123).\n";
} else {
    $super = User::where('username', 'superadmin')->first();
    $super->role_id = 6;
    $super->save();
    echo "Superadmin role ensured for user 'superadmin'.\n";
}
