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
            $table->boolean('bl_confirmed')->default(false);
            $table->integer('post_bl_edit_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_instructions', function (Blueprint $table) {
            $table->dropColumn('bl_confirmed');
            $table->dropColumn('post_bl_edit_count');
        });
    }
};
