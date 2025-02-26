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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->date('booking_date');
            $table->string('booking_number')->unique();
            $table->string('quotation_number')->nullable();
            $table->string('service')->nullable();
            $table->string('liner_address')->nullable();
            $table->string('shipper')->nullable();
            $table->string('contact_shipper')->nullable();
            $table->string('consignee')->nullable();
            $table->string('contact_consignee')->nullable();
            $table->string('vessel')->nullable();
            $table->string('place_of_receipt')->nullable();
            $table->string('pol')->nullable(); // Port of Loading
            $table->string('pod')->nullable(); // Port of Discharge
            $table->string('voyage')->nullable();
            $table->string('place_of_delivery')->nullable();
            $table->datetime('ets')->nullable(); // Estimated Time of Sailing
            $table->datetime('eta')->nullable(); // Estimated Time of Arrival
            $table->enum('status', ['New', 'Pending', 'Confirmed', 'Shipped', 'Completed', 'Cancelled'])->default('New');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // Booking created by which user
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
