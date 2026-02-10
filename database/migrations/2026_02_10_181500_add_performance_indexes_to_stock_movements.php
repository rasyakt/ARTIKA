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
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->index('type');
            $table->index('created_at');
            $table->index(['type', 'created_at']);
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->index('quantity');
            $table->index('expired_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['type', 'created_at']);
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->dropIndex(['quantity']);
            $table->dropIndex(['expired_at']);
        });
    }
};
