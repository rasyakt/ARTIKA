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
        Schema::create('identity_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'nis', 'nisn', 'nik'
            $table->string('label'); // e.g., 'NIS', 'NISN', 'NIK'
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identity_types');
    }
};
