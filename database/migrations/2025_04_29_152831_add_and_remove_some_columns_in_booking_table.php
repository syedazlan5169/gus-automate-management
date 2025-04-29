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
            $table->string('tug')->nullable()->after('eta');
            $table->string('delivery_terms')->default('Port to Port')->after('tug');
            //Drop some columns
            $table->dropColumn('quotation_number');
            $table->dropColumn('liner_address');
            $table->dropColumn('remarks');
            $table->dropColumn('internal_instructions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('quotation_number')->nullable();
            $table->string('liner_address')->nullable();
            $table->string('remarks')->nullable();
            $table->string('internal_instructions')->nullable();
            $table->dropColumn('tug');
            $table->dropColumn('delivery_terms');
        });
    }
};
