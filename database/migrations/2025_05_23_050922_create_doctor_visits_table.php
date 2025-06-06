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
        Schema::create('doctor_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->string('visit_description')->nullable();
            $table->decimal('visit_charges', 8, 2)->nullable();
            $table->enum('status', ['Active', 'Inactive'])->nullable();
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_visits');
    }
};
