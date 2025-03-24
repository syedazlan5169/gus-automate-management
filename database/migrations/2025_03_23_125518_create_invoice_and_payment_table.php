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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings');
            $table->string('invoice_number');
            $table->date('invoice_date');
            $table->decimal('invoice_amount', 10, 2);
            $table->string('payment_terms');
            $table->string('status')->default('Unpaid');
            $table->string('invoice_file');
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices');
            $table->decimal('payment_amount', 10, 2);
            $table->date('payment_date');
            $table->string('payment_method');
            $table->string('status')->default('Pending Confirmation');
            $table->string('payment_file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('payments');
    }
};
