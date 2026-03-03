<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('is_active');
        });

        // Set initial sort order based on existing ID order
        $methods = DB::table('payment_methods')->orderBy('id')->get();
        foreach ($methods as $index => $method) {
            DB::table('payment_methods')->where('id', $method->id)->update(['sort_order' => $index]);
        }
    }

    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
