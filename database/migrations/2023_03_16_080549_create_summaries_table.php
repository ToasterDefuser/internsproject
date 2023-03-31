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
        Schema::create('summaries', function (Blueprint $table) {
            $table->id()->index();
            $table->string('TotalLines');
            $table->string('TotalNetAmount');
            $table->string('TotalTaxableBasis');
            $table->string('TotalTaxAmount');
            $table->string('TotalGrossAmount');

            // tax summary
            $table->string('TaxRate');
            $table->string('TaxCategoryCode');
            $table->string('TaxAmount');
            $table->string('TaxableBasis');
            $table->string('TaxableAmount');
            $table->string('GrossAmount');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summaries');
    }
};
