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
        Schema::table('stocks', function (Blueprint $table) {
            $table->index(['product_id', 'expired_at'], 'idx_stocks_product_expiry');
        });

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->index('product_id', 'idx_trans_items_product_id');
            $table->index('transaction_id', 'idx_trans_items_transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks_and_transactions', function (Blueprint $table) {
            //
        });
    }
};
