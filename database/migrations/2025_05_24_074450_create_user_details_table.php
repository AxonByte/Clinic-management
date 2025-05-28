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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
             $table->date('dob')->nullable();
             $table->string('nationalid')->nullable();
             $table->string('gender')->nullable();
             $table->string('height')->nullable();
             $table->string('weight')->nullable();
             $table->string('allergies')->nullable();
             $table->string('medical_history')->nullable();
             $table->string('emergency_contact_person')->nullable();
             $table->boolean('sms')->nullable();
            $table->timestamps();
            $table->foreign('user_id')
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
        Schema::dropIfExists('user_details');
    }
};
