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
        Schema::table('edit_after_telex', function (Blueprint $table) {
            $table->json('snapshot_before')->nullable()->after('edited_by');
            $table->json('snapshot_after')->nullable()->after('snapshot_before');
            $table->json('changes')->nullable()->after('snapshot_after');
            $table->timestamp('finalized_at')->nullable()->after('changes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('edit_after_telex', function (Blueprint $table) {
            $table->dropColumn('snapshot_before');
            $table->dropColumn('snapshot_after');
            $table->dropColumn('changes');
            $table->dropColumn('finalized_at');
        });
    }
};
