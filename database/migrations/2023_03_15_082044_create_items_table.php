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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('LineNumber');
            $table->string('EAN')->index();
            $table->string('SupplierItemCode');
            $table->string('ItemDescription');
            $table->string('ItemType');
            $table->string('InvoiceQuantity');
            $table->string('UnitOfMeasure');
            $table->string('InvoiceUnitPacksize');
            $table->string('PackItemUnitOfMeasure');
            $table->string('InvoiceUnitNetPrice');
            $table->string('TaxRate');
            $table->string('TaxCategoryCode');
            $table->string('TaxAmount');
            $table->string('NetAmount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
