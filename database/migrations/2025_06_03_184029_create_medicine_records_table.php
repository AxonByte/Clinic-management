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
        Schema::create('medicine_records', function (Blueprint $table) {
            $table->id();
            $table->string('generic_name')->nullable(); // e.g., "Paracetamol"
            $table->string('brand_name')->nullable();   // e.g., "Crocin"
            $table->string('invoice_no')->nullable();
            $table->decimal('sales_price', 10, 2)->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->foreignId('patient_admitted_id')->nullable()->constrained('patient_admitteds')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_records');
    }
};
