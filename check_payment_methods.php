<?php
use Illuminate\Support\Facades\DB;
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$methods = DB::table('transactions')->distinct()->pluck('payment_method');
echo "Payment Methods: " . json_encode($methods) . "\n";
