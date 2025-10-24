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
        Schema::table('si_change_requests', function (Blueprint $table) {
            $table->text('cancellation_reason')->nullable()->after('approver_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('si_change_requests', function (Blueprint $table) {
            $table->dropColumn('cancellation_reason');
        });
    }
};
