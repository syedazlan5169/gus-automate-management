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
        Schema::create('si_change_requests', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('booking_id')
                ->constrained('bookings')
                ->cascadeOnDelete();

            $table->foreignId('shipping_instruction_id')
                ->constrained('shipping_instructions')
                ->cascadeOnDelete();

            $table->foreignId('requested_by_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Workflow + meta
            // status: submitted | under_review | approved_for_edit | customer_editing |
            //         pending_final_review | approved_applied | rejected | cancelled | expired
            $table->string('status')->default('submitted');
            $table->text('reason');

            // Field-level control
            $table->json('requested_fields');      // array of SI field keys requested by customer
            $table->json('approved_fields')->nullable(); // subset approved by admin

            // Approvals and notes
            $table->foreignId('approver_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('approver_note')->nullable();

            // Edit window (optional: we’ll enforce later)
            $table->timestamp('expires_at')->nullable();

            // Snapshots for audit (before/after)
            $table->json('prechange_snapshot')->nullable();
            $table->json('postchange_snapshot')->nullable();

            // Who actually applied final changes
            $table->foreignId('applied_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            // Helpful indexes
            $table->index(['shipping_instruction_id', 'status']);
            $table->index(['booking_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('si_change_requests');
    }
};
