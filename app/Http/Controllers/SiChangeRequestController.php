<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ShippingInstruction;
use App\Models\SiChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SiChangeRequestController extends Controller
{
    public function store(Request $request, ShippingInstruction $si)
    {
        $user = $request->user();
        $booking = $si->booking; // owner = bookings.user_id

        // Basic authorization: must be the booking owner & a customer
        if (!$booking || (int)$booking->user_id !== (int)$user->id || $user->role !== 'customer') {
            abort(403, 'Unauthorized');
        }

        // Optional: ensure ALL SIs telex released (your visibility rule)
        $allTelexReleased = $booking->shippingInstructions->every(fn ($s) => (bool)$s->telex_bl_released);
        if (!$allTelexReleased) {
            return back()->with('warning', 'All SIs must be Telex Released before requesting changes.');
        }

        // Ensure no active request exists for this SI
        $hasActive = $si->changeRequests()
            ->whereNotIn('status', [
                SiChangeRequest::STATUS_APPROVED_APPLIED,
                SiChangeRequest::STATUS_REJECTED,
                SiChangeRequest::STATUS_CANCELLED,
                SiChangeRequest::STATUS_EXPIRED,
            ])->exists();

        if ($hasActive) {
            return back()->with('warning', 'There is already an active change request for this SI.');
        }

        // Validate payload
        $data = $request->validate([
            'requested_fields'   => ['required', 'array', 'min:1'],
            'requested_fields.*' => ['string'],
            'reason'             => ['required', 'string', 'min:3', 'max:1000'],
        ]);

        // Create request
        SiChangeRequest::create([
            'booking_id'              => $booking->id,
            'shipping_instruction_id' => $si->id,
            'requested_by_user_id'    => $user->id,
            'status'                  => SiChangeRequest::STATUS_UNDER_REVIEW,
            'reason'                  => $data['reason'],
            'requested_fields'        => array_values($data['requested_fields']),
            // we'll fill prechange_snapshot later when admin approves fields
        ]);

        return back()->with('success', 'Change request submitted. We’ll review it shortly.');
    }

    public function approveFields(Request $http, \App\Models\SiChangeRequest $request)
    {
        $user = $http->user();

        // BASIC AUTHZ: only non-customer can approve
        if ($user->role === 'customer') {
            abort(403, 'Unauthorized');
        }

        // Must be in under_review to approve
        if ($request->status !== \App\Models\SiChangeRequest::STATUS_UNDER_REVIEW) {
            return back()->with('warning', 'Only requests under review can be approved.');
        }

        // Validate admin’s field choices (subset of originally requested_fields)
        $data = $http->validate([
            'approved_fields'   => ['required', 'array', 'min:1'],
            'approved_fields.*' => ['string'],
            'approver_note'     => ['nullable', 'string', 'max:1000'],
        ]);

        // Guard: ensure approved_fields ⊆ requested_fields
        $original = collect($request->requested_fields ?? []);
        $approved = collect($data['approved_fields'] ?? []);
        if ($approved->isEmpty() || !$approved->every(fn($f) => $original->contains($f))) {
            return back()->with('warning', 'Approved fields must be a subset of requested fields.');
        }

        // Capture the SI snapshot for later diff
        $si = $request->shippingInstruction; // via relation (works if you added it)
        if (!$si) {
            return back()->with('warning', 'Related Shipping Instruction not found.');
        }

        // keep the snapshot small & explicit (we can expand later)
        $snapshot = $si->only([
            'id','shipper','shipper_address','consignee','consignee_address',
            'notify_party','notify_party_address','cargo_description',
            'hs_code','gross_weight','volume','box_operator','sub_booking_number'
        ]);

        $request->update([
            'status'             => \App\Models\SiChangeRequest::STATUS_APPROVED_FOR_EDIT,
            'approved_fields'    => $approved->values()->all(),
            'approver_user_id'   => $user->id,
            'approver_note'      => $data['approver_note'] ?? null,
            'prechange_snapshot' => $snapshot,
            // optional: editing window
            // 'expires_at' => now()->addHours(24),
        ]);

        return back()->with('success', 'Fields approved. Customer may edit approved fields only.');
    }

    public function customerCancel(\Illuminate\Http\Request $http, \App\Models\SiChangeRequest $request)
    {
        $user = $http->user();

        // Only the booking owner (customer) can cancel, and only in approved_for_edit
        $si = $request->shippingInstruction;      // requires relation on model
        $booking = $si?->booking;

        if (!$booking || (int)$booking->user_id !== (int)$user->id || $user->role !== 'customer') {
            abort(403, 'Unauthorized');
        }
        if ($request->status !== \App\Models\SiChangeRequest::STATUS_APPROVED_FOR_EDIT) {
            return back()->with('warning', 'You can only cancel while the request is approved for edit.');
        }

        $data = $http->validate([
            'cancel_reason' => ['required', 'string', 'min:3', 'max:1000'],
        ]);

        $request->update([
            'status'              => SiChangeRequest::STATUS_CANCELLED,
            'cancelled_by_user_id' => $user->id,
            'cancel_reason'       => $data['cancel_reason'],
            'cancelled_at'         => now(),
        ]);

        return back()->with('success', 'Your change request has been cancelled.');
    }

    public function finalApprove(\Illuminate\Http\Request $http, \App\Models\SiChangeRequest $request)
    {
        $user = $http->user();
        if ($user->role === 'customer') abort(403);

        if ($request->status !== \App\Models\SiChangeRequest::STATUS_PENDING_FINAL_REVIEW) {
            return back()->with('warning', 'Request is not pending final review.');
        }

        $data = $http->validate([
            'final_note' => ['nullable','string','max:2000'],
        ]);

        $si = $request->shippingInstruction;
        $booking = $si?->booking;
        if (!$si || !$booking) return back()->with('warning', 'Related SI/Booking not found.');

        // Only apply approved fields that were actually submitted in draft_changes
        $approved  = collect($request->approved_fields ?? []);
        $draft     = collect($request->draft_changes ?? []);
        $toApply   = $draft->only($approved->all()); // intersection

        if ($toApply->isEmpty()) {
            return back()->with('warning', 'Nothing to apply. Draft is empty or no approved fields present.');
        }

        // Normalize address arrays back to expected casts (already arrays)
        $fillable = [
            'shipper','shipper_address','consignee','consignee_address',
            'notify_party','notify_party_address','cargo_description',
            'hs_code','gross_weight','volume','box_operator','sub_booking_number'
        ];

        // Apply only known/whitelisted SI columns
        $applyPayload = $toApply->only($fillable)->toArray();

        DB::transaction(function () use ($si, $applyPayload, $request, $user, $data) {
            // OPTIONAL: bump your post-BL edit counter on the SI (you referenced it in the UI)
            if (Schema::hasColumn($si->getTable(), 'post_bl_edit_count')) {
                $si->post_bl_edit_count = (int) $si->post_bl_edit_count + 1;
            }

            $si->fill($applyPayload);
            $si->save();

            // Save postchange snapshot (after apply) for audit
            $postSnap = $si->only([
                'id','shipper','shipper_address','consignee','consignee_address',
                'notify_party','notify_party_address','cargo_description',
                'hs_code','gross_weight','volume','box_operator','sub_booking_number'
            ]);

            $request->update([
                'status'                => \App\Models\SiChangeRequest::STATUS_APPROVED_APPLIED,
                'postchange_snapshot'   => $postSnap,
                'final_note'            => $data['final_note'] ?? null,
                'final_reviewer_user_id'=> $user->id,
                'final_decision_at'     => now(),
            ]);
        });

        return redirect()
            ->route('booking.show', $booking)
            ->with('success', 'Changes approved and applied to the Shipping Instruction.');
    }

    public function finalReject(\Illuminate\Http\Request $http, \App\Models\SiChangeRequest $request)
    {
        $user = $http->user();
        if ($user->role === 'customer') abort(403);

        if ($request->status !== \App\Models\SiChangeRequest::STATUS_PENDING_FINAL_REVIEW) {
            return back()->with('warning', 'Request is not pending final review.');
        }

        $data = $http->validate([
            'final_note' => ['required','string','min:3','max:2000'], // reason required on reject
        ]);

        $si = $request->shippingInstruction;
        $booking = $si?->booking;
        if (!$si || !$booking) return back()->with('warning', 'Related SI/Booking not found.');

        $request->update([
            'status'                 => \App\Models\SiChangeRequest::STATUS_REJECTED,
            'final_note'             => $data['final_note'],
            'final_reviewer_user_id' => $user->id,
            'final_decision_at'      => now(),
        ]);

        return redirect()
            ->route('booking.show', $booking)
            ->with('success', 'Change request rejected and the customer has been notified.');
    }


}
