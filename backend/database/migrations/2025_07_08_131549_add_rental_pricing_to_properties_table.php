<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Add rental pricing fields
            $table->decimal('price_per_month', 15, 2)->nullable()->after('price');
            $table->decimal('price_per_day', 15, 2)->nullable()->after('price_per_month');
            
            // Rename the existing price field to be more specific
            $table->renameColumn('price', 'sale_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Remove rental pricing fields
            $table->dropColumn(['price_per_month', 'price_per_day']);
            
            // Rename back to original
            $table->renameColumn('sale_price', 'price');
        });
    }
};
