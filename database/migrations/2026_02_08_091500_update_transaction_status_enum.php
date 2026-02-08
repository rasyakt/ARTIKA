<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // Drop existing check constraint if it exists (Laravel's convention)
            DB::statement('ALTER TABLE transactions DROP CONSTRAINT IF EXISTS transactions_status_check');
            // Add new check constraint
            DB::statement("ALTER TABLE transactions ADD CONSTRAINT transactions_status_check CHECK (status IN ('pending', 'completed', 'canceled', 'partial_return', 'returned'))");
        } else {
            // MySQL/MariaDB raw statement
            DB::statement("ALTER TABLE `transactions` MODIFY COLUMN `status` ENUM('pending', 'completed', 'canceled', 'partial_return', 'returned') DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE transactions DROP CONSTRAINT IF EXISTS transactions_status_check');
            DB::statement("ALTER TABLE transactions ADD CONSTRAINT transactions_status_check CHECK (status IN ('pending', 'completed', 'canceled'))");
        } else {
            DB::statement("ALTER TABLE `transactions` MODIFY COLUMN `status` ENUM('pending', 'completed', 'canceled') DEFAULT 'pending'");
        }
    }
};
