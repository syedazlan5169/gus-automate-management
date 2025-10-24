<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Booking;
use App\Models\ShippingInstruction;
use App\Models\User;

class SiChangeRequest extends Model
{
    // Optional: if you plan to mass-assign
    protected $guarded = [];

    /**
     * Prototype status constants (kept flexible while we build).
     * We’ll tighten validation later.
     */
    public const STATUS_UNDER_REVIEW         = 'under_review';
    public const STATUS_APPROVED_FOR_EDIT    = 'approved_for_edit';
    public const STATUS_PENDING_FINAL_REVIEW = 'pending_final_review';
    public const STATUS_APPROVED_APPLIED     = 'approved_applied';
    public const STATUS_REJECTED             = 'rejected';
    public const STATUS_CANCELLED            = 'cancelled';
    public const STATUS_EXPIRED              = 'expired';

    protected $casts = [
        'requested_fields'     => 'array',
        'approved_fields'      => 'array',
        'prechange_snapshot'   => 'array',
        'draft_changes'        => 'array',
        'postchange_snapshot'  => 'array',
        'submitted_at'         => 'datetime',
        'cancelled_at'         => 'datetime',
        'final_decision_at'    => 'datetime',
    ];


    // Active = not terminal
    public function scopeActive($q)
    {
        return $q->whereNotIn('status', [
            self::STATUS_APPROVED_APPLIED,
            self::STATUS_REJECTED,
            self::STATUS_CANCELLED,
            self::STATUS_EXPIRED,
        ]);
    }

    public function scopeForStatus($q, string $status)
    {
        return $q->where('status', $status);
    }

    // Quick check for terminality
    public function isTerminal(): bool
    {
        return in_array($this->status, [
            self::STATUS_APPROVED_APPLIED,
            self::STATUS_REJECTED,
            self::STATUS_CANCELLED,
            self::STATUS_EXPIRED,
        ], true);
    }

    public function canCustomerCancel(): bool
    {
        return $this->status === self::STATUS_APPROVED_FOR_EDIT;
    }

    /** Relationships */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function shippingInstruction(): BelongsTo
    {
        return $this->belongsTo(ShippingInstruction::class, 'shipping_instruction_id');
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_user_id');
    }

    public function appliedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applied_by_user_id');
    }
}
