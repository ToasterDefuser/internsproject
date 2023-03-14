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
        Schema::create('Buyer', function (Blueprint $table) {
            $table->id();
            $table->string('ILN');
            $table->string('TaxID');
            $table->string('Name');
            $table->string('StreetAndNumber');
            $table->string('CityName');
            $table->string('PostalCode');
            $table->string('Country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Buyer');
    }
};
