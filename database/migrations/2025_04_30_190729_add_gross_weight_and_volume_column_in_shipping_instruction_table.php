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
            $table->decimal('gross_weight', 10, 2)->nullable()->after('hs_code');
            $table->decimal('volume', 10, 2)->nullable()->after('gross_weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_instructions', function (Blueprint $table) {
            $table->dropColumn('gross_weight');
            $table->dropColumn('volume');
        });
    }
};
