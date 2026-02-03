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
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('expense_category_id')->after('date')->nullable()->constrained('expense_categories')->onDelete('cascade');
        });

        // Default categories
        $defaultCategories = ['Gaji', 'Sewa', 'Listrik/Air', 'Transportasi', 'Lainnya'];
        foreach ($defaultCategories as $cat) {
            DB::table('expense_categories')->insertOrIgnore([
                'name' => $cat,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Migrate existing expenses data
        $expenses = DB::table('expenses')->get();
        foreach ($expenses as $expense) {
            $categoryName = $expense->category;
            $category = DB::table('expense_categories')->where('name', $categoryName)->first();

            if (!$category) {
                $categoryId = DB::table('expense_categories')->insertGetId([
                    'name' => $categoryName,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                $categoryId = $category->id;
            }

            DB::table('expenses')->where('id', $expense->id)->update([
                'expense_category_id' => $categoryId
            ]);
        }

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('category');
            $table->unsignedBigInteger('expense_category_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('category')->after('date')->nullable();
        });

        $expenses = DB::table('expenses')->get();
        foreach ($expenses as $expense) {
            $categoryName = DB::table('expense_categories')->where('id', $expense->expense_category_id)->value('name');
            DB::table('expenses')->where('id', $expense->id)->update([
                'category' => $categoryName
            ]);
        }

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['expense_category_id']);
            $table->dropColumn('expense_category_id');
        });
    }
};
