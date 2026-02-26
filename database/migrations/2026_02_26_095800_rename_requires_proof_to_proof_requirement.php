<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->string('proof_requirement', 20)->default('disabled')->after('slug');
        });

        // Migrate existing data
        DB::table('payment_methods')->where('requires_proof', true)->update(['proof_requirement' => 'required']);
        DB::table('payment_methods')->where('requires_proof', false)->update(['proof_requirement' => 'disabled']);

        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('requires_proof');
        });
    }

    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->boolean('requires_proof')->default(false)->after('slug');
        });

        DB::table('payment_methods')->where('proof_requirement', 'required')->update(['requires_proof' => true]);
        DB::table('payment_methods')->whereIn('proof_requirement', ['optional', 'disabled'])->update(['requires_proof' => false]);

        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('proof_requirement');
        });
    }
};
