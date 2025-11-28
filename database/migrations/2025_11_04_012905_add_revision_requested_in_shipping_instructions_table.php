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
            $table->integer('number_of_revisions_requested')->default(0)->after('telex_bl_released');
            $table->integer('number_of_revisions_applied')->default(0)->after('number_of_revisions_requested');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_instructions', function (Blueprint $table) {
            $table->dropColumn('number_of_revisions_requested');
            $table->dropColumn('number_of_revisions_applied');
        });
    }
};
