<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ShippingInstruction;
use App\Models\SiChangeRequest;
use App\Models\CargoContainer;
use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SiChangeRequestUnderReview;
use App\Mail\SiChangeRequestRejected;
use App\Mail\SiChangeRequestApprovedForEdit;
use App\Mail\SiChangeRequestRejectedFinal;
use App\Mail\SiChangeRequestApprovedApplied;
use App\Mail\SiChangeRequestCancelledByCustomer;


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
        $changeRequest = SiChangeRequest::create([
            'booking_id'              => $booking->id,
            'shipping_instruction_id' => $si->id,
            'requested_by_user_id'    => $user->id,
            'status'                  => SiChangeRequest::STATUS_UNDER_REVIEW,
            'reason'                  => $data['reason'],
            'requested_fields'        => array_values($data['requested_fields']),
            // we'll fill prechange_snapshot later when admin approves fields
        ]);

        // Send notification emails
        try {
            if ($user->email) {
                Mail::to($user->email)->send(new SiChangeRequestUnderReview($changeRequest, 'customer'));
            }
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send customer email for SI change request under review: ' . $e->getMessage());
        }
        
        try {
            if (config('mail.admin_to')) {
                Mail::to(config('mail.admin_to'))->send(new SiChangeRequestUnderReview($changeRequest, 'admin'));
            }
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send admin email for SI change request under review: ' . $e->getMessage());
        }

        return back()->with('success', 'Change request submitted. We\'ll review it shortly.');
    }

    public function approveFields(Request $http, SiChangeRequest $request)
    {
        $user = $http->user();

        // BASIC AUTHZ: only non-customer can approve
        if ($user->role === 'customer') {
            abort(403, 'Unauthorized');
        }

        // Must be in under_review to approve
        if ($request->status !== SiChangeRequest::STATUS_UNDER_REVIEW) {
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

        // If containers are approved, capture containers snapshot
        if ($approved->contains('containers')) {
            $containersSnapshot = $si->containers->map(function ($container) {
                return [
                    'id' => $container->id,
                    'cargo_id' => $container->cargo_id,
                    'container_number' => $container->container_number,
                    'seal_number' => $container->seal_number,
                ];
            })->toArray();
            $snapshot['containers'] = $containersSnapshot;
        }

        $request->update([
            'status'             => SiChangeRequest::STATUS_APPROVED_FOR_EDIT,
            'approved_fields'    => $approved->values()->all(),
            'approver_user_id'   => $user->id,
            'approver_note'      => $data['approver_note'] ?? null,
            'prechange_snapshot' => $snapshot,
            // optional: editing window
            // 'expires_at' => now()->addHours(24),
        ]);

        // Send approval notification to customer
        try {
            $requester = $request->requester;
            if ($requester && $requester->email) {
                Mail::to($requester->email)->send(new SiChangeRequestApprovedForEdit($request->fresh()));
            }
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send customer email for SI change request approved for edit: ' . $e->getMessage());
        }

        return back()->with('success', 'Fields approved. Customer may edit approved fields only.');
    }

    public function rejectRequest(Request $http, SiChangeRequest $request)
    {
        $user = $http->user();

        // BASIC AUTHZ: only non-customer can reject
        if ($user->role === 'customer') {
            abort(403, 'Unauthorized');
        }

        // Must be in under_review to reject
        if ($request->status !== SiChangeRequest::STATUS_UNDER_REVIEW) {
            return back()->with('warning', 'Only requests under review can be rejected.');
        }

        // Validate rejection note (required)
        $data = $http->validate([
            'rejection_note' => ['required', 'string', 'min:3', 'max:1000'],
        ]);

        // Reject the request
        $request->update([
            'status'             => SiChangeRequest::STATUS_REJECTED,
            'approver_user_id'   => $user->id,
            'approver_note'      => $data['rejection_note'],
            'final_reviewer_user_id' => $user->id,
            'final_decision_at' => now(),
        ]);

        // Send rejection notification to customer
        try {
            $requester = $request->requester;
            if ($requester && $requester->email) {
                Mail::to($requester->email)->send(new SiChangeRequestRejected($request->fresh()));
            }
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send customer email for SI change request rejected: ' . $e->getMessage());
        }

        return redirect()
            ->route('booking.show', $request->booking)
            ->with('success', 'Change request has been rejected and the customer has been notified.');
    }

    public function customerCancel(Request $http, SiChangeRequest $request)
    {
        $user = $http->user();

        // Only the booking owner (customer) can cancel, and only in approved_for_edit
        $si = $request->shippingInstruction;      // requires relation on model
        $booking = $si?->booking;

        if (!$booking || (int)$booking->user_id !== (int)$user->id || $user->role !== 'customer') {
            abort(403, 'Unauthorized');
        }
        if ($request->status !== SiChangeRequest::STATUS_APPROVED_FOR_EDIT) {
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

        // Send cancellation notification to admin
        try {
            if (config('mail.admin_to')) {
                Mail::to(config('mail.admin_to'))->send(new SiChangeRequestCancelledByCustomer($request->fresh()));
            }
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send admin email for SI change request cancelled by customer: ' . $e->getMessage());
        }

        return back()->with('success', 'Your change request has been cancelled.');
    }

    public function finalDecide(Request $http, SiChangeRequest $changeRequest)
    {
        $user = $http->user();
        if ($user->role === 'customer') abort(403);

        if ($changeRequest->status !== SiChangeRequest::STATUS_PENDING_FINAL_REVIEW) {
            return back()->with('warning', 'Request is not pending final review.');
        }

        // decide: 'approve' or 'reject'
        $decision = $http->string('decision'); // returns Strable; fine to compare
        if (!in_array((string) $decision, ['approve', 'reject'], true)) {
            return back()->with('warning', 'Invalid decision.');
        }

        // Shared lookups
        $si = $changeRequest->shippingInstruction;
        $booking = $si?->booking;
        if (!$si || !$booking) {
            return back()->with('warning', 'Related SI/Booking not found.');
        }

        // Validation differs slightly based on decision
        $rules = [
            'final_note' => ['nullable','string','max:2000'],
        ];
        if ((string) $decision === 'reject') {
            $rules['final_note'] = ['required','string','min:3','max:2000'];
        }

        $data = $http->validate($rules);

        if ((string) $decision === 'approve') {
            // Only apply approved fields that were submitted in draft_changes
            $approved = collect($changeRequest->approved_fields ?? []);
            $draft    = collect($changeRequest->draft_changes ?? []);
            $toApply  = $draft->only($approved->all()); // intersection

            if ($toApply->isEmpty()) {
                return back()->with('warning', 'Nothing to apply. Draft is empty or no approved fields present.');
            }

            // Whitelist SI columns that are safe to fill
            $fillable = [
                'shipper','shipper_address','consignee','consignee_address',
                'notify_party','notify_party_address','cargo_description',
                'hs_code','gross_weight','volume','box_operator','sub_booking_number',
            ];
            $applyPayload = $toApply->only($fillable)->toArray();
            $hasContainers = $approved->contains('containers') && isset($toApply['containers']);

            DB::transaction(function () use ($si, $applyPayload, $changeRequest, $user, $data, $toApply, $hasContainers) {
                // Optional post-BL edit counter
                if (Schema::hasColumn($si->getTable(), 'post_bl_edit_count')) {
                    $si->post_bl_edit_count = (int) $si->post_bl_edit_count + 1;
                }

                $si->fill($applyPayload);
                $si->save();

                // Handle containers if approved and in draft_changes
                if ($hasContainers && isset($toApply['containers'])) {
                    $this->applyContainerChanges($si, $toApply['containers']);
                }

                // Snapshot after apply for audit
                $postSnap = $si->only([
                    'id','shipper','shipper_address','consignee','consignee_address',
                    'notify_party','notify_party_address','cargo_description',
                    'hs_code','gross_weight','volume','box_operator','sub_booking_number',
                ]);

                // Include containers in post-change snapshot if they were changed
                if ($hasContainers) {
                    $containersSnapshot = $si->containers->map(function ($container) {
                        return [
                            'id' => $container->id,
                            'cargo_id' => $container->cargo_id,
                            'container_number' => $container->container_number,
                            'seal_number' => $container->seal_number,
                        ];
                    })->toArray();
                    $postSnap['containers'] = $containersSnapshot;
                }

                $changeRequest->update([
                    'status'                  => SiChangeRequest::STATUS_APPROVED_APPLIED,
                    'postchange_snapshot'     => $postSnap,
                    'final_note'              => $data['final_note'] ?? null,
                    'final_reviewer_user_id'  => $user->id,
                    'final_decision_at'       => now(),
                ]);
            });

            // Send approval notification to customer after transaction completes
            try {
                $requester = $changeRequest->fresh()->requester;
                if ($requester && $requester->email) {
                    Mail::to($requester->email)->send(new SiChangeRequestApprovedApplied($changeRequest->fresh()));
                }
            } catch (\Exception $e) {
                // Log error but don't fail the request
                \Log::error('Failed to send customer email for SI change request approved and applied: ' . $e->getMessage());
            }

            return redirect()
                ->route('booking.show', $booking)
                ->with('success', 'Changes approved and applied to the Shipping Instruction.');
        }

        // Reject path
        $changeRequest->update([
            'status'                  => SiChangeRequest::STATUS_REJECTED,
            'final_note'              => $data['final_note'],
            'final_reviewer_user_id'  => $user->id,
            'final_decision_at'       => now(),
        ]);

        // Send final rejection notification to customer
        try {
            $requester = $changeRequest->requester;
            if ($requester && $requester->email) {
                Mail::to($requester->email)->send(new SiChangeRequestRejectedFinal($changeRequest->fresh()));
            }
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send customer email for SI change request rejected final: ' . $e->getMessage());
        }

        return redirect()
            ->route('booking.show', $booking)
            ->with('success', 'Change request rejected and the customer has been notified.');
    }

    public function timeline(SiChangeRequest $changeRequest)
    {
        // Basic authorization: user should have access to the booking
        $booking = $changeRequest->booking;
        $user = request()->user();

        // Check if user has access (customer owns booking, or staff/admin)
        $hasAccess = false;
        if ($user->role === 'customer') {
            $hasAccess = (int)$booking->user_id === (int)$user->id;
        } else {
            $hasAccess = true; // Staff/admin have access
        }

        if (!$hasAccess) {
            abort(403, 'Unauthorized');
        }

        return response()->json([
            'timeline' => $changeRequest->timeline(),
            'request' => [
                'id' => $changeRequest->id,
                'status' => $changeRequest->status,
                'created_at' => $changeRequest->created_at?->toIso8601String(),
            ],
        ]);
    }

    /**
     * Apply container changes to the shipping instruction
     */
    private function applyContainerChanges(ShippingInstruction $si, array $containersData): void
    {
        // Track new container counts per cargo
        $cargoContainerCounts = [];

        // Get all container numbers from the draft changes
        $requestContainers = collect($containersData)
            ->flatMap(function ($containerGroup) {
                return collect($containerGroup)->pluck('container_number');
            })
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        // Get existing containers for this shipping instruction
        $existingContainers = $si->containers()
            ->pluck('container_number')
            ->unique()
            ->values()
            ->toArray();

        // Find containers that need to be released (in existing but not in request)
        $containersToRelease = array_diff($existingContainers, $requestContainers);

        // Release containers that are no longer in the request
        if (!empty($containersToRelease)) {
            CargoContainer::whereIn('container_number', $containersToRelease)
                ->where('shipping_instruction_id', $si->id)
                ->update(['shipping_instruction_id' => null]);
        }

        // Update or create containers
        foreach ($containersData as $cargoId => $containerGroup) {
            // Initialize counter for this cargo if not exists
            if (!isset($cargoContainerCounts[$cargoId])) {
                $cargoContainerCounts[$cargoId] = 0;
            }

            foreach ($containerGroup as $container) {
                if (empty($container['container_number']) && empty($container['seal_number'])) {
                    continue;
                }

                $containerNumber = $container['container_number'] ?? '';

                // Check if this container already exists in this shipping instruction
                if (!empty($containerNumber) && in_array($containerNumber, $existingContainers)) {
                    // Update the existing container
                    CargoContainer::where('container_number', $containerNumber)
                        ->where('shipping_instruction_id', $si->id)
                        ->update([
                            'seal_number' => $container['seal_number'] ?? '',
                        ]);
                } elseif (!empty($containerNumber)) {
                    // This is a new container, look for an available container to reuse
                    $availableContainer = CargoContainer::where('cargo_id', $cargoId)
                        ->whereNull('shipping_instruction_id')
                        ->first();

                    if ($availableContainer) {
                        // Reuse the available container
                        $availableContainer->update([
                            'container_number' => $containerNumber,
                            'shipping_instruction_id' => $si->id,
                            'seal_number' => $container['seal_number'] ?? '',
                        ]);
                    } else {
                        // Create a new container and increment the counter
                        CargoContainer::create([
                            'cargo_id' => $cargoId,
                            'shipping_instruction_id' => $si->id,
                            'container_number' => $containerNumber,
                            'seal_number' => $container['seal_number'] ?? '',
                        ]);
                        $cargoContainerCounts[$cargoId]++;
                    }
                }
            }
        }

        // Update cargo container_count for any cargo that had new containers added
        foreach ($cargoContainerCounts as $cargoId => $addedCount) {
            if ($addedCount > 0) {
                $cargo = Cargo::find($cargoId);
                if ($cargo) {
                    $cargo->container_count += $addedCount;
                    $cargo->save();
                }
            }
        }
    }

}
