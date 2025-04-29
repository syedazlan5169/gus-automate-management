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
        Schema::table('shipping_instructions', function (Blueprint $table) {
            $table->dropColumn('contact_shipper');
            $table->dropColumn('contact_consignee');
            $table->dropColumn('notify_party_address');

            //add new columns
            $table->string('shipper_contact')->nullable()->after('shipper');
            $table->json('shipper_address')->nullable()->after('shipper_contact');
            $table->string('consignee_contact')->nullable()->after('consignee');
            $table->json('consignee_address')->nullable()->after('consignee_contact');
            $table->json('notify_party_address')->nullable()->after('notify_party_contact');
            $table->string('bl_number')->nullable()->after('sub_booking_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_instructions', function (Blueprint $table) {
            $table->dropColumn('shipper_contact');
            $table->dropColumn('shipper_address');
            $table->dropColumn('consignee_contact');
            $table->dropColumn('consignee_address');
            $table->dropColumn('notify_party_address');
            $table->dropColumn('bl_number');
            $table->string('contact_shipper')->nullable()->after('shipper');
            $table->string('contact_consignee')->nullable()->after('consignee');
            $table->string('notify_party_address')->nullable()->after('notify_party_contact');
            
        });
    }
};
