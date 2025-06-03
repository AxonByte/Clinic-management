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
        Schema::create('patient_admitteds', function (Blueprint $table) {
            $table->id();
            $table->timestamp('admission_time')->nullable();
            $table->string('category')->nullable();
            $table->unsignedBigInteger('bed_category_id')->nullable();
            $table->unsignedBigInteger('bed_id')->nullable();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->unsignedBigInteger('accepting_doctor_id')->nullable();
            $table->string('blood_group', 5)->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('diagnosis_at_hospitalization')->nullable();
            $table->text('other_illnesses')->nullable();
            $table->text('history')->nullable();
            $table->text('reactions')->nullable();
            $table->string('transferred_from')->nullable();

            // Add foreign key constraints if the related tables exist
            $table->foreign('bed_id')->references('id')->on('beds')->onDelete('set null');
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('bed_category_id')->references('id')->on('bed_categories')->onDelete('set null');
            $table->foreign('accepting_doctor_id')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_admitteds');
    }
};
