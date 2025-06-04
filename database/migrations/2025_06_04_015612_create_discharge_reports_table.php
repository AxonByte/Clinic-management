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
        Schema::create('discharge_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_admitted_id')->nullable()->constrained('patient_admitteds')->onDelete('cascade');
            $table->foreignId('doctor_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('discharge_time')->nullable();
            $table->text('final_diagnosis')->nullable();
            $table->text('anatomopatologic_diagnosis')->nullable();
            $table->text('checkout_diagnosis')->nullable();
            $table->text('checkout_state')->nullable();
            $table->text('initial_diagnosis')->nullable();
            $table->text('medicine_description')->nullable();
            $table->text('instruction')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discharge_reports');
    }
};
