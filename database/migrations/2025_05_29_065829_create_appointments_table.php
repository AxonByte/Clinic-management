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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->date('appointment_date')->nullable();
            $table->string('visit_type')->constrained('doctor_visits')->onDelete('cascade');
            $table->string('time_slot')->nullable();
            $table->decimal('visit_charges', 8, 2)->nullable();
            $table->decimal('discount', 8, 2)->default(0)->nullable();
            $table->decimal('total_amount', 8, 2)->nullable();
            $table->boolean('pay_now')->default(false);
            $table->string('status')->default('Pending Confirmation');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
