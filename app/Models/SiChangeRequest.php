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

    // Derived, read-only timeline for quick audit
    public function timeline(): array
    {
        $events = [];

        // 1) Submitted (under_review)
        $events[] = [
            'label' => 'Submitted (Under review)',
            'at'    => $this->created_at,
            'by'    => optional($this->requester)->name ?? 'Customer',
            'note'  => $this->reason,
        ];

        // 2) Approved for edit
        if ($this->status === self::STATUS_APPROVED_FOR_EDIT || !empty($this->approved_fields)) {
            // If draft was submitted, approval happened before submission
            // Use a timestamp between created_at and submitted_at to maintain correct order
            if (!empty($this->submitted_at) && $this->submitted_at->isAfter($this->created_at)) {
                // Use midpoint between created_at and submitted_at as proxy
                $approvalTime = $this->created_at->copy()->addSeconds(
                    $this->created_at->diffInSeconds($this->submitted_at) / 2
                );
            } else {
                // No submission yet, or edge case - use updated_at but ensure it's after created_at
                $approvalTime = $this->updated_at;
                if ($this->created_at && $approvalTime->isBefore($this->created_at)) {
                    $approvalTime = $this->created_at->copy()->addMinutes(1);
                }
            }
            
            $events[] = [
                'label' => 'Approved for edit',
                'at'    => $approvalTime,
                'by'    => optional($this->approver)->name,
                'note'  => $this->approver_note,
                'meta'  => [
                    'approved_fields' => $this->approved_fields ?: [],
                ],
            ];
        }

        // 3) Customer canceled (optional)
        if (!empty($this->cancelled_at)) {
            $events[] = [
                'label' => 'Canceled by customer',
                'at'    => $this->cancelled_at,
                'by'    => optional($this->requester)->name ?? 'Customer',
                'note'  => $this->cancel_reason,
            ];
        }

        // 4) Draft submitted (pending_final_review)
        if (!empty($this->submitted_at)) {
            $events[] = [
                'label' => 'Draft submitted (Pending final review)',
                'at'    => $this->submitted_at,
                'by'    => optional($this->requester)->name ?? 'Customer',
                'meta'  => [
                    'draft_changes_keys' => array_keys($this->draft_changes ?? []),
                ],
            ];
        }

        // 5) Final decision (approved/rejected/expired)
        if (!empty($this->final_decision_at)) {
            $events[] = [
                'label' => match ($this->status) {
                    self::STATUS_APPROVED_APPLIED => 'Approved & Applied',
                    self::STATUS_REJECTED         => 'Rejected',
                    self::STATUS_EXPIRED          => 'Expired',
                    default                       => 'Finalized',
                },
                'at'   => $this->final_decision_at,
                'by'   => optional($this->finalReviewer)->name ?? optional($this->approver)->name,
                'note' => $this->final_note ?? $this->approver_note, // Use approver_note if final_note is empty (for early rejections)
            ];
        }

        // 6) Rejection at fields phase (if rejected early without final_decision_at)
        if ($this->status === self::STATUS_REJECTED && empty($this->final_decision_at) && !empty($this->approver_note)) {
            $events[] = [
                'label' => 'Rejected',
                'at'    => $this->updated_at,
                'by'    => optional($this->approver)->name,
                'note'  => $this->approver_note,
            ];
        }

        // Sort by time asc just in case
        usort($events, fn($a,$b) => ($a['at']?->timestamp ?? 0) <=> ($b['at']?->timestamp ?? 0));

        return $events;
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

    // Alias for consistency in timeline
    public function requester(): BelongsTo
    {
        return $this->requestedBy();
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_user_id');
    }

    public function finalReviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'final_reviewer_user_id');
    }

    public function appliedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applied_by_user_id');
    }
}
