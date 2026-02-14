<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('supplier_pre_order_items', function (Blueprint $table) {
            $table->string('unit_name')->default('Pcs')->after('product_id');
            $table->integer('pcs_per_unit')->default(1)->after('unit_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_pre_order_items', function (Blueprint $table) {
            $table->dropColumn(['unit_name', 'pcs_per_unit']);
        });
    }
};
