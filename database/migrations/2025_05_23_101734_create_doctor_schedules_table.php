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
        Schema::create('doctor_schedules', function (Blueprint $table) {
           $table->id();
           $table->unsignedBigInteger('doctor_id');
           $table->string('weekday')->nullable();
           $table->time('start_time')->nullable();
           $table->time('end_time')->nullable();
           $table->string('appointment_duration')->nullable();
           $table->timestamps();

            $table->foreign('doctor_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
