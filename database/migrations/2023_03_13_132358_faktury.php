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
        Schema::create('faktury', function (Blueprint $table) {
            $table->id();
            $table->string('Kontrahent');
            $table->string('Faktura');
            $table->string('Data wystawienia');
            $table->timestamps('Data wpływu');
            $table->string('Kwota Netto');
            $table->string('Kwota Brutto');
            $table->string('Zamówienie');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faktury');
    }
};