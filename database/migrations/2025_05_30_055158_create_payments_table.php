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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')
                ->constrained()
                ->onDelete('cascade');

            $table->decimal('amount', 10, 2)->default(0);
            $table->string('deposit_type')->nullable(); // e.g., Cash, Card, UPI
            $table->string('card_type')->nullable();    // e.g., Visa, MasterCard
            $table->string('card_holder_name')->nullable();
            $table->string('card_number')->nullable();
            $table->string('cvv')->nullable();
            $table->date('expiry_date')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->string('status')->default('pending'); // pending, success, failed
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
