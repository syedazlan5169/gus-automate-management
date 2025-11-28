<?php

namespace App\Http\Controllers;

use App\Models\ShippingInstruction;
use App\Models\SiChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SiChangeRequestPendingFinalReview;

class SiApprovedEditController extends Controller
{
    public function edit(Request $http, ShippingInstruction $si, SiChangeRequest $request)
    {
        $user = $http->user();
        $booking = $si->booking;

        // Must be booking owner & customer
        if (!$booking || (int)$booking->user_id !== (int)$user->id || $user->role !== 'customer') {
            abort(403, 'Unauthorized');
        }

        // The request must belong to this SI and be approved_for_edit
        if ((int)$request->shipping_instruction_id !== (int)$si->id) {
            abort(404);
        }
        if ($request->status !== SiChangeRequest::STATUS_APPROVED_FOR_EDIT) {
            return redirect()->route('booking.show', $booking)
                ->with('warning', 'This request is not approved for editing.');
        }

        $approvedFields = collect($request->approved_fields ?? [])->values()->all();

        // Keep field labels consistent with your modal
        $fieldLabels = [
            'shipper'              => 'Shipper',
            'shipper_address'      => 'Shipper Address',
            'consignee'            => 'Consignee',
            'consignee_address'    => 'Consignee Address',
            'notify_party'         => 'Notify Party',
            'notify_party_address' => 'Notify Party Address',
            'cargo_description'    => 'Cargo Description',
            'hs_code'              => 'HS Code',
            'gross_weight'         => 'Gross Weight',
            'volume'               => 'Volume',
            'box_operator'         => 'Box Operator',
            'containers'           => 'Containers List',
        ];

        return view('si-change-requests.edit-approved', [
            'si'             => $si,
            'booking'        => $booking,
            'changeRequest'  => $request,
            'approvedFields' => $approvedFields,
            'fieldLabels'    => $fieldLabels,
        ]);
    }

    public function submit(Request $http, ShippingInstruction $si, SiChangeRequest $request)
    {
        $user = $http->user();
        $booking = $si->booking;

        // Must be booking owner & customer
        if (!$booking || (int)$booking->user_id !== (int)$user->id || $user->role !== 'customer') {
            abort(403, 'Unauthorized');
        }

        // Ensure request belongs to SI and is in approved_for_edit
        if ((int)$request->shipping_instruction_id !== (int)$si->id) {
            abort(404);
        }
        if ($request->status !== SiChangeRequest::STATUS_APPROVED_FOR_EDIT) {
            return redirect()->route('booking.show', $booking)
                ->with('warning', 'This request is not open for editing.');
        }

        // Only accept values for approved fields
        $approved = collect($request->approved_fields ?? [])->values();

        if ($approved->isEmpty()) {
            return back()->with('warning', 'No fields were approved for editing.');
        }

        // Build validation rules dynamically for the approved fields
        $rules = [];
        $hasContainers = $approved->contains('containers');
        
        foreach ($approved as $field) {
            // Containers are handled separately
            if ($field === 'containers') {
                continue;
            }
            
            // simple, conservative rules; adjust per field if needed later
            if (in_array($field, ['shipper_address','consignee_address','notify_party_address'], true)) {
                $rules[$field] = ['nullable','string','max:5000']; // textarea text; we'll split to array
            } else {
                $rules[$field] = ['nullable','string','max:1000'];
            }
        }

        // Add containers validation if approved
        if ($hasContainers) {
            $rules['containers'] = ['nullable', 'array'];
            $rules['containers.*'] = ['array'];
            $rules['containers.*.*.container_number'] = ['nullable', 'string', 'max:50'];
            $rules['containers.*.*.seal_number'] = ['nullable', 'string', 'max:50'];
            $rules['containers.*.*.container_type'] = ['nullable', 'integer', 'exists:cargos,id'];
        }

        $data = $http->validate($rules);

        // Normalize addresses (textarea → array of non-empty lines)
        foreach (['shipper_address','consignee_address','notify_party_address'] as $addrField) {
            if ($approved->contains($addrField) && array_key_exists($addrField, $data)) {
                $lines = preg_split('/\r\n|\r|\n/', (string)$data[$addrField]);
                $data[$addrField] = array_values(array_filter(array_map('trim', $lines), fn($v) => $v !== ''));
            }
        }

        // Process containers if approved - clean and structure the data
        if ($hasContainers && isset($data['containers'])) {
            // Ensure containers data is properly structured
            $containersData = [];
            foreach ($data['containers'] as $cargoId => $containerGroup) {
                if (!is_array($containerGroup)) {
                    continue;
                }
                foreach ($containerGroup as $index => $container) {
                    if (!empty($container['container_number']) || !empty($container['seal_number'])) {
                        $containersData[$cargoId][] = [
                            'container_number' => $container['container_number'] ?? '',
                            'seal_number' => $container['seal_number'] ?? '',
                            'cargo_id' => (int)$cargoId,
                        ];
                    }
                }
            }
            $data['containers'] = $containersData;
        } elseif ($hasContainers) {
            // If containers was approved but no data provided, set empty array
            $data['containers'] = [];
        }

        // Save draft & flip state
        $request->update([
            'draft_changes' => $data, // only approved keys present
            'status'        => SiChangeRequest::STATUS_PENDING_FINAL_REVIEW,
            'submitted_at'  => now(),
        ]);

        // Send notification emails to both customer and admin
        try {
            $requester = $request->requester;
            if ($requester && $requester->email) {
                Mail::to($requester->email)->send(new SiChangeRequestPendingFinalReview($request->fresh(), 'customer'));
            }
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send customer email for SI change request pending final review: ' . $e->getMessage());
        }
        
        try {
            if (config('mail.admin_to')) {
                Mail::to(config('mail.admin_to'))->send(new SiChangeRequestPendingFinalReview($request->fresh(), 'admin'));
            }
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send admin email for SI change request pending final review: ' . $e->getMessage());
        }

        return redirect()
            ->route('booking.show', $booking)
            ->with('success', 'Your edits were submitted and are pending final review.');
    }

}
