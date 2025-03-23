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
            $table->string('vessel')->nullable();
            $table->string('place_of_receipt')->nullable();
            $table->string('pol')->nullable(); // Port of Loading
            $table->string('pod')->nullable(); // Port of Discharge
            $table->string('voyage')->nullable();
            $table->string('place_of_delivery')->nullable();
            $table->datetime('ets')->nullable(); // Estimated Time of Sailing
            $table->datetime('eta')->nullable(); // Estimated Time of Arrival
            $table->enum('status', ['Pending SI', 'Pending Invoice', 'Pending Payment', 'Complete Payment', 'Shipped', 'Completed', 'Cancelled'])->default('Pending SI');
            $table->text('remarks')->nullable();
            $table->text('internal_instructions')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });

        // New table for shipping instructions
        Schema::create('shipping_instructions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('sub_booking_number')->nullable();
            $table->string('box_operator')->nullable();
            $table->string('shipper');
            $table->string('contact_shipper');
            $table->string('consignee');
            $table->string('contact_consignee');
            $table->string('notify_party');
            $table->string('notify_party_contact');
            $table->string('notify_party_address');
            $table->string('cargo_description');
            $table->string('hs_code');
            $table->timestamps();
        });

        // Modified cargos table to link with shipping instructions
        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained();
            $table->string('container_type');
            $table->integer('container_count');    // Total containers allocated for this type
            $table->decimal('total_weight', 10, 2);
            $table->timestamps();
        });

        Schema::create('cargo_containers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cargo_id')->constrained()->onDelete('cascade');
            $table->foreignId('shipping_instruction_id')->nullable()->constrained()->onDelete('set null');
            $table->string('container_number')->nullable();
            $table->string('seal_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargo_containers');
        Schema::dropIfExists('cargos');
        Schema::dropIfExists('shipping_instructions');
        Schema::dropIfExists('bookings');
    }
};
