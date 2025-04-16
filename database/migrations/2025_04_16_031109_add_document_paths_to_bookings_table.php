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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('container_load_list')->nullable();
            $table->string('notice_of_arrival')->nullable();
            $table->string('towing_certificate')->nullable();
            $table->string('vendor_invoice')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'container_load_list',
                'notice_of_arrival',
                'towing_certificate',
                'vendor_invoice'
            ]);
        });
    }
};
