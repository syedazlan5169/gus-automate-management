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
            $table->text('final_note')->nullable()->after('approver_note');
            $table->unsignedBigInteger('final_reviewer_user_id')->nullable()->after('final_note');
            $table->timestamp('final_decision_at')->nullable()->after('final_reviewer_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('si_change_requests', function (Blueprint $table) {
            $table->dropColumn('final_note');
            $table->dropColumn('final_reviewer_user_id');
            $table->dropColumn('final_decision_at');
        });
    }
};
