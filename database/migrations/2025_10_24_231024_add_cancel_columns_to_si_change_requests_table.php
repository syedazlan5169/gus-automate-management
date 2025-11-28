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
            $table->unsignedBigInteger('cancelled_by_user_id')->nullable()->after('approver_user_id');
            $table->text('cancel_reason')->nullable()->after('cancelled_by_user_id');
            $table->timestamp('cancelled_at')->nullable()->after('cancel_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('si_change_requests', function (Blueprint $table) {
            $table->dropColumn('cancelled_by_user_id');
            $table->dropColumn('cancel_reason');
            $table->dropColumn('cancelled_at');
        });
    }
};
