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
            $table->json('draft_changes')->nullable()->after('prechange_snapshot');
            $table->timestamp('submitted_at')->nullable()->after('draft_changes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('si_change_requests', function (Blueprint $table) {
            $table->dropColumn('draft_changes');
            $table->dropColumn('submitted_at');
        });
    }
};
