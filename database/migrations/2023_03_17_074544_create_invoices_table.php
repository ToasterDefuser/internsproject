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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('InvoiceNumber');
            $table->string('InvoiceDate');
            $table->string('SalesDate');
            $table->string('InvoiceCurrency');
            $table->string('InvoicePaymentDueDate');
            $table->string('InvoicePaymentTerms');
            $table->string('DocumentFunctionCode');

            // rel with Orders
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders');

            // rel with Delivery
            $table->unsignedBigInteger('delivery_id')->nullable();
            $table->foreign('delivery_id')->references('id')->on('deliveries');

            // rel with Buyer
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->foreign('buyer_id')->references('id')->on('buyers');
            
            // rel with Seller
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->foreign('seller_id')->references('id')->on('sellers');

            // rel with Items
            $table->unsignedBigInteger('summary_id')->nullable();
            $table->foreign('summary_id')->references('id')->on('summaries');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
        dropConstrainedForeignId('order_id');
        dropConstrainedForeignId('delivery_id');
        dropConstrainedForeignId('buyer_id');
        dropConstrainedForeignId('seller_id');
        dropConstrainedForeignId('summary_id');
    }
};
