<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Invoice', function (Blueprint $table) {
            $table->id();
            $table->string('InvoiceNumber');
            $table->string('InvoiceDate');
            $table->string('SalesDate');
            $table->string('InvoiceCurrency');
            $table->string('InvoicePaymentDueDate');
            $table->string('DocumentFunctionCode');

            // order
            $table->string('BuyerOrderNumber');
            $table->string('BuyerOrderDate');

            // delivery
            $table->string('DeliveryLocationNumber');
            $table->string('DeliveryDate');
            $table->string('DespatchNumber');


            // rel 1:1
            $table->string('BuyerILN');
            $table->string('SellerILN');
            // 1:N
            $table->string('EAN');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Invoice');
    }
};
