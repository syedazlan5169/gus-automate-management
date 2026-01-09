<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\ShippingRoute;
use App\Models\BookingStatus;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingStatusUpdated;
use App\Mail\InvoiceUploaded;
use App\Mail\PaymentVerification;
use App\Models\ShippingInstruction;
use App\Models\ActivityLog;
use App\Models\Voyage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\EditAfterTelex;

class BookingController extends Controller
{
    /**
     * Display a listing of the bookings.
     */
    public function index()
    {
        if (auth()->user()->role === 'customer')
        {
            $bookings = Booking::where('user_id', auth()->id())->get();
            
            // Get status labels for each booking
            $statusLabels = [];
            foreach ($bookings as $booking) {
                $statusLabels[$booking->id] = BookingStatus::labels($booking->status)[$booking->status] ?? 'Unknown';
            }
            
            return view('booking.index', compact('bookings', 'statusLabels'));
        }
        else
        {
            $bookings = Booking::all();
            
            // Get status labels for each booking
            $statusLabels = [];
            foreach ($bookings as $booking) {
                $statusLabels[$booking->id] = BookingStatus::labels($booking->status)[$booking->status] ?? 'Unknown';
            }
            
            return view('booking.index', compact('bookings', 'statusLabels'));
        }
    }

    // Show the form for creating a new booking.
    public function create()
    {
        $shippingRoutes = ShippingRoute::all();
        return view('bookings.create', compact('shippingRoutes'));
    }

    // Show Edit page for a booking
    public function edit(Booking $booking)
    {
        $booking->load('cargos');
        $shippingRoutes = ShippingRoute::all();
        return view('booking.edit', compact('booking', 'shippingRoutes'));
    }

    // Store a newly created booking in storage.
    public function store(Request $request)
    {
        $validated = $request->validate([
            
            // Route Information
            'place_of_receipt' => 'required|string|max:255',
            'pol' => 'required|string|max:255',
            'pod' => 'required|string|max:255',
            'place_of_delivery' => 'required|string|max:255',
            
            // Schedule
            'ets' => 'required|date|after:today',
            
            // Cargo Details
            'container_type' => 'required|array',
            'container_type.*' => 'required|string',
            'container_count' => 'required|array',
            'container_count.*' => 'required|integer|min:1',
            'total_weight' => 'required|array',
            'total_weight.*' => 'required|numeric|min:0',
        ]);

        try {
            \DB::beginTransaction();

            // Generate a unique booking number
            $yearMonth = now()->format('ym'); // YYMM format like '2505'
            $prefix = 'G' . $yearMonth;
            
            // Lock the table while checking latest booking number
            $latestBooking = Booking::where('booking_number', 'like', $prefix . '%')
                ->orderByDesc('booking_number')
                ->lockForUpdate()
                ->first();
            
            if ($latestBooking) {
                $lastRunningNumber = (int) substr($latestBooking->booking_number, -4);
                $newRunningNumber = str_pad($lastRunningNumber + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $newRunningNumber = '0001';
            }
            
            $bookingNumber = $prefix . $newRunningNumber;
            

            // Create the booking
            $booking = Booking::create([
                'booking_number' => $bookingNumber,
                'booking_date' => now(),
                'place_of_receipt' => $validated['place_of_receipt'],
                'pol' => $validated['pol'],
                'pod' => $validated['pod'],
                'place_of_delivery' => $validated['place_of_delivery'],
                'ets' => $validated['ets'],
                'delivery_terms' => 'Port to Port',
                'user_id' => auth()->id(),
                'status' => 1,
            ]);

            // Store cargo details
            foreach ($validated['container_type'] as $index => $containerType) {
                // Create cargo record
                $cargo = $booking->cargos()->create([
                    'container_type' => $containerType,
                    'container_count' => $validated['container_count'][$index],
                    'total_weight' => $validated['total_weight'][$index],
                ]);

                // Create placeholder container records
                for ($i = 0; $i < $validated['container_count'][$index]; $i++) {
                    $cargo->containers()->create([
                        'container_number' => null,
                        'seal_number' => null,
                    ]);
                }
            }

            \DB::commit();


            ActivityLog::logBookingCreated(auth()->user(), $booking);

            if (auth()->user()->role === 'customer') {
                return redirect()->route('booking.show', $booking)
                    ->with('success', 'Booking created successfully.');
            } else {
                return redirect()->route('booking.show', $booking)
                    ->with('success', 'Booking created successfully.');
            }

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Error creating booking: ' . $e->getMessage());
        }
    }

    // Display the specified booking.
    public function show(Booking $booking)
    {
        $booking->load([
            'cargos.containers', 
            'shippingInstructions.containers', 
            'invoice.payment'
        ]);
        $statusLabel = BookingStatus::labels($booking->status)[$booking->status] ?? 'Unknown';
        $status = new BookingStatus();

        return view('booking.show', compact('booking', 'status', 'statusLabel'));
    }

    // Static class to store the snapshot of the booking before and after the edit
    public static function bookingFields(): array {
        return [
            'voyage_id','ets','eta',
        ];
    }

    // Static class to store the snapshot of the shipping instruction before and after the edit
    public static function siFields(): array {
        return [
            'bl_number',
            'box_operator',
            'shipper',
            'shipper_contact',
            'shipper_address',
            'consignee',
            'consignee_contact',
            'consignee_address',
            'notify_party',
            'notify_party_contact',
            'notify_party_address',
            'cargo_description',
            'hs_code',
            'gross_weight',
            'volume',
        ];
    }

    // Static class to identify datetime fields
    public static function datetimeFields(): array {
        return [
            'ets', 'eta', 'booking_date', 'created_at', 'updated_at'
        ];
    }

    // Static class to format datetime values for display
    public static function formatDateTimeValue($value, $field) {
        if (empty($value)) {
            return '';
        }

        // Check if this is a datetime field
        if (in_array($field, self::datetimeFields())) {
            try {
                // Handle Carbon instances or datetime strings
                if ($value instanceof \Carbon\Carbon) {
                    // Format date fields without time
                    if ($field === 'booking_date') {
                        return $value->format('d-m-Y');
                    }
                    // Format datetime fields with time
                    return $value->format('d-m-Y H:i:s');
                }
                
                // Handle datetime strings
                if (is_string($value)) {
                    $carbon = \Carbon\Carbon::parse($value);
                    // Format date fields without time
                    if ($field === 'booking_date') {
                        return $carbon->format('d-m-Y');
                    }
                    // Format datetime fields with time
                    return $carbon->format('d-m-Y H:i:s');
                }
            } catch (\Exception $e) {
                // If parsing fails, return the original value
                return $value;
            }
        }

        return $value;
    }

    // Static class to compute the diff between the snapshot before and after the edit
    public static function diff(array $before, array $after, array $fields): array {
        $out = [];
        foreach ($fields as $f) {
            $old = $before[$f] ?? null;
            $new = $after[$f] ?? null;
            if ($old !== $new) {
                // If the before value is already formatted (string), use it as is
                // Otherwise, format it
                $formattedOld = is_string($old) && !empty($old) ? $old : self::formatDateTimeValue($old, $f);
                $formattedNew = is_string($new) && !empty($new) ? $new : self::formatDateTimeValue($new, $f);
                
                $out[$f] = [
                    'from' => $formattedOld,
                    'to' => $formattedNew
                ];
            }
        }
        return $out;
    }

    // Make changes after telex bl released
    public function enableEdit(Booking $booking, Request $request)
    {
        $booking->update(['enable_edit' => true]);
        $booking->load('shippingInstructions');

        // Format booking data before storing
        $bookingBefore = $booking->only($this->bookingFields());
        $formattedBookingBefore = [];
        foreach ($bookingBefore as $field => $value) {
            $formattedBookingBefore[$field] = self::formatDateTimeValue($value, $field);
        }

        $siBefore = [];
        foreach ($booking->shippingInstructions as $si) {
            $siData = $si->only($this->siFields());
            $formattedSiData = [];
            foreach ($siData as $field => $value) {
                $formattedSiData[$field] = self::formatDateTimeValue($value, $field);
            }
            
            $siBefore[(string)$si->id] = [
                'before' => $formattedSiData,
                'change_type' => 'updated',
            ];
        }

        EditAfterTelex::create([
            'booking_id' => $booking->id,
            'request_by' => $request->request_by,
            'request_date' => $request->request_date,
            'request_reason' => $request->request_reason,
            'edited_by' => $request->edited_by,
            'snapshot_before' => [
                'booking' => [
                    'id' => $booking->id,
                    'before' => $formattedBookingBefore,
                ],
                'shipping_instructions' => $siBefore,
            ],
        ]);

        return redirect()->route('booking.show', $booking)->with('success', 'Edit after BL confirmed is enabled.');
    }

    // Stop editing
    public function disableEdit(Booking $booking)
    {
        $booking->update(['enable_edit' => false]);

        // grab the latest open session for this booking
        $log = EditAfterTelex::where('booking_id', $booking->id)
            ->whereNull('finalized_at')
            ->latest('id')
            ->first();

        if (!$log) {
            return back()->with('error', 'No open edit session found.');
        }

        $booking->load('shippingInstructions');

        // Booking diff
        $bookingFields = $this->bookingFields();
        $beforeBooking = data_get($log->snapshot_before, 'booking.before', []);
        $afterBooking = $booking->only($bookingFields);
        
        // Format after booking data for comparison
        $formattedAfterBooking = [];
        foreach ($afterBooking as $field => $value) {
            $formattedAfterBooking[$field] = self::formatDateTimeValue($value, $field);
        }
        
        $bookingChanges = self::diff($beforeBooking, $formattedAfterBooking, $bookingFields);

        // SI diffs (created/updated/deleted)
        $beforeSis = (array) data_get($log->snapshot_before, 'shipping_instructions', []);
        $afterSis = [];
        foreach ($booking->shippingInstructions as $si) {
            $siData = $si->only($this->siFields());
            $formattedSiData = [];
            foreach ($siData as $field => $value) {
                $formattedSiData[$field] = self::formatDateTimeValue($value, $field);
            }
            $afterSis[(string)$si->id] = $formattedSiData;
        }
        $siChanges = [];

        // updated/created
        foreach ($afterSis as $id => $afterData) {
            $beforeData = data_get($beforeSis, "$id.before");
            if ($beforeData === null) {
                // created during edit window
                $siChanges[$id] = [
                    'before' => null,
                    'after' => $afterData,
                    'changes' => self::diff([], $afterData, $this->siFields()),
                    'change_type' => 'created',
                ];
            } else {
                $diff = self::diff($beforeData, $afterData, $this->siFields());
                $siChanges[$id] = [
                    'before' => $beforeData,
                    'after' => $afterData,
                    'changes' => $diff,
                    'change_type' => 'updated',
                ];
            }
        }

        // deleted
        foreach ($beforeSis as $id => $wrapped) {
            if (!array_key_exists($id, $afterSis)) {
                $siChanges[$id] = [
                    'before' => data_get($wrapped, 'before', []),
                    'after' => null,
                    'changes' => ['deleted' => true],
                    'change_type' => 'deleted',
                ];
            }
        }

        $log->update([
            'snapshot_after' => [
                'booking' => ['id' => $booking->id, 'after' => $formattedAfterBooking],
                'shipping_instructions' => collect($afterSis)->map(fn($v) => ['after' => $v])->all(),
            ],
            'changes' => [
                'booking' => $bookingChanges,
                'shipping_instructions' => $siChanges,
            ],
            'finalized_at' => now(),
        ]);

        return back()->with('success', 'Edit after BL confirmed is disabled and logged.');
    }


    // Update the specified booking in storage.
    public function update(Request $request, Booking $booking)
    {
        // Log the incoming request data
        \Log::info('Booking Update Request Data:', [
            'all_data' => $request->all(),
            'booking_id' => $booking->id
        ]);

        try {

            // Create a new voyage
            $voyageNumber = strtoupper(trim($request->voyage));
            $voyageExists = false;
            $voyageExists = Voyage::where('voyage_number', $voyageNumber)->exists();
            $voyage = Voyage::firstOrCreate(
                ['voyage_number' => $voyageNumber],
                ['last_bl_suffix' => 400]
            );
            $booking->update(['voyage_id' => $voyage->id]);

            // Separate booking and cargo validation
            $bookingValidated = $request->validate([
                'vessel' => 'sometimes|required|string|max:255',
                'place_of_receipt' => 'sometimes|required|string|max:255',
                'pol' => 'sometimes|required|string|max:255',
                'pod' => 'sometimes|required|string|max:255',
                'place_of_delivery' => 'sometimes|required|string|max:255',
                'ets' => 'sometimes|required|date',
                'eta' => 'sometimes|required|date|after:ets',
                'delivery_terms' => 'sometimes|required|string|max:255',
                'tug' => 'sometimes|required|string|max:255',
            ]);

            $cargoValidated = $request->validate([
                'container_type' => 'sometimes|required|array',
                'container_type.*' => 'required|string',
                'container_count' => 'sometimes|required|array',
                'container_count.*' => 'required|integer|min:1',
                'total_weight' => 'sometimes|required|array',
                'total_weight.*' => 'required|numeric|min:0',
            ]);

            \Log::info('Validated Data:', [
                'booking' => $bookingValidated,
                'cargo' => $cargoValidated
            ]);

            \DB::beginTransaction();
            \Log::info('Transaction started');

            \Log::info('Updating booking with validated data');
            // Update the booking with only booking fields
            $booking->update($bookingValidated);
            \Log::info('Booking updated successfully');

            // Update cargo information if provided (only if status < 3)
            if (isset($cargoValidated['container_type']) && $booking->status < 3) {
                \Log::info('Updating cargo information');
                
                // Before deleting, preserve container relationships to shipping instructions
                // Group containers by container_type for easier matching
                $existingContainersByType = [];
                foreach ($booking->cargos as $cargo) {
                    if (!isset($existingContainersByType[$cargo->container_type])) {
                        $existingContainersByType[$cargo->container_type] = [];
                    }
                    foreach ($cargo->containers as $container) {
                        $existingContainersByType[$cargo->container_type][] = [
                            'shipping_instruction_id' => $container->shipping_instruction_id,
                            'container_number' => $container->container_number,
                            'seal_number' => $container->seal_number,
                        ];
                    }
                }
                
                \Log::info('Preserved containers by type:', array_map('count', $existingContainersByType));
                
                // Delete existing cargos (this will cascade delete containers)
                $booking->cargos()->delete();
                \Log::info('Existing cargos deleted');

                // Create new cargos
                foreach ($cargoValidated['container_type'] as $index => $containerType) {
                    $cargo = $booking->cargos()->create([
                        'container_type' => $containerType,
                        'container_count' => $cargoValidated['container_count'][$index],
                        'total_weight' => $cargoValidated['total_weight'][$index],
                    ]);

                    \Log::info('Created cargo:', [
                        'cargo_id' => $cargo->id,
                        'container_type' => $containerType,
                        'container_count' => $cargoValidated['container_count'][$index],
                        'total_weight' => $cargoValidated['total_weight'][$index]
                    ]);

                    // Get available containers for this type
                    $availableContainers = $existingContainersByType[$containerType] ?? [];
                    
                    // Create container records and restore shipping_instruction_id links
                    for ($i = 0; $i < $cargoValidated['container_count'][$index]; $i++) {
                        // Try to find a matching container from the preserved list
                        // Priority: containers with shipping_instruction_id (allocated containers)
                        $matchingContainer = null;
                        $matchingIndex = null;
                        
                        // First, try to match containers that have shipping_instruction_id (allocated)
                        foreach ($availableContainers as $key => $existing) {
                            if ($existing['shipping_instruction_id']) {
                                $matchingContainer = $existing;
                                $matchingIndex = $key;
                                break;
                            }
                        }
                        
                        // If no allocated container found, use the first available (unallocated)
                        if (!$matchingContainer && !empty($availableContainers)) {
                            $matchingContainer = $availableContainers[0];
                            $matchingIndex = 0;
                        }
                        
                        // Remove matched container from available list
                        if ($matchingIndex !== null) {
                            unset($availableContainers[$matchingIndex]);
                            $availableContainers = array_values($availableContainers); // Re-index
                        }
                        
                        // Create container with preserved shipping_instruction_id if available
                        $containerData = [
                            'container_number' => null,
                            'seal_number' => null,
                        ];
                        
                        if ($matchingContainer) {
                            $containerData['container_number'] = $matchingContainer['container_number'];
                            $containerData['seal_number'] = $matchingContainer['seal_number'];
                            
                            if ($matchingContainer['shipping_instruction_id']) {
                                $containerData['shipping_instruction_id'] = $matchingContainer['shipping_instruction_id'];
                            }
                        }
                        
                        $container = $cargo->containers()->create($containerData);
                        \Log::info('Created container:', [
                            'container_id' => $container->id,
                            'shipping_instruction_id' => $containerData['shipping_instruction_id'] ?? null,
                            'container_number' => $containerData['container_number']
                        ]);
                    }
                }
            } else {
                \Log::info('No cargo information provided in request');
            }

            ActivityLog::logBookingEdited(auth()->user(), $booking);
            \Log::info('Activity log created');

            \DB::commit();
            \Log::info('Transaction committed successfully');

            if ($voyageExists) {
                return redirect()->route('booking.show', $booking)
                    ->with('warning', 'This voyage number has been used in another booking.')
                    ->with('success', 'Booking updated successfully.');
            }

            return redirect()->route('booking.show', $booking)
                ->with('success', 'Booking updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            \Log::warning('Booking validation failed', [
                'booking_id' => $booking->id,
                'errors' => $e->errors()
            ]);
            return back()->withErrors($e->errors())
                        ->withInput();
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error updating booking:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return back()->with('error', 'Error updating booking: ' . $e->getMessage());
        }
    }

    // Remove the specified booking from storage.
    public function destroy(Booking $booking)
    {
        if ($booking->status != 0) {
            return back()->with('error', 'Only new or cancelled bookings can be deleted.');
        }
        
        try {
            \DB::beginTransaction();
            
            // Delete related invoice and payment if they exist
            if ($booking->invoice) {
                if ($booking->invoice->payment) {
                    $booking->invoice->payment->delete();
                }
                $booking->invoice->delete();
            }
            
            // Delete related shipping instructions
            foreach ($booking->shippingInstructions as $shippingInstruction) {
                $shippingInstruction->delete();
            }
            
            // Delete related cargos and their containers
            foreach ($booking->cargos as $cargo) {
                // Delete containers related to this cargo
                $cargo->containers()->delete();
                $cargo->delete();
            }
            
            // Finally delete the booking
            $booking->delete();
            // Log the deletion
            ActivityLog::logBookingDeleted(auth()->user(), $booking);

            \DB::commit();
            return redirect()->route('bookings.index')
                ->with('success', 'Booking deleted successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Error deleting booking: ' . $e->getMessage());
        }
    }

    // Add new method to get available containers for shipping instructions
    public function getAvailableContainers(Booking $booking)
    {
        $availableContainers = $booking->cargos()
            ->with(['containers' => function ($query) {
                $query->whereNull('shipping_instruction_id');
            }])
            ->get()
            ->map(function ($cargo) {
                return [
                    'container_type' => $cargo->container_type,
                    'available_containers' => $cargo->containers
                        ->whereNull('shipping_instruction_id')
                        ->values()
                ];
            });

        return response()->json($availableContainers);
    }

    public function submitBooking(Booking $booking)
    {
        $booking->update(['sub_status' => 1]);
        Mail::to($booking->user->email)->send(new BookingStatusUpdated($booking, 'customer'));
        Mail::to(config('mail.admin_to'))->send(new BookingStatusUpdated($booking, 'admin'));
        ActivityLog::logBookingSubmitted(auth()->user(), $booking);
        return redirect()->route('booking.show', $booking)->with('success', 'Booking submitted successfully.');
    }

    public function confirmBooking(Booking $booking)
    {
        $booking->update(['status' => 2, 'sub_status' => 0]);
        Mail::to($booking->user->email)->send(new BookingStatusUpdated($booking, 'customer'));
        Mail::to(config('mail.admin_to'))->send(new BookingStatusUpdated($booking, 'admin'));
        ActivityLog::logBookingConfirmed(auth()->user(), $booking);
        return redirect()->route('booking.show', $booking)->with('success', 'Booking confirmed successfully.');
    }

    public function submitSI(Booking $booking)
    {
        $booking->update(['status' => 3]);
        Mail::to($booking->user->email)->send(new BookingStatusUpdated($booking, 'customer'));
        Mail::to(config('mail.admin_to'))->send(new BookingStatusUpdated($booking, 'admin'));
        ActivityLog::logShippingInstructionSubmitted(auth()->user(), $booking);
        return redirect()->route('booking.show', $booking)->with('success', 'Shipping Instructions submitted successfully.');
    }

    public function confirmBL(Booking $booking)
    {
        $booking->update(['status' => 4]);
        // Update each shipping instruction individually
        foreach ($booking->shippingInstructions as $shippingInstruction) {
            $shippingInstruction->update(['bl_confirmed' => true]);
        }
        Mail::to($booking->user->email)->send(new BookingStatusUpdated($booking, 'customer'));
        Mail::to(config('mail.admin_to'))->send(new BookingStatusUpdated($booking, 'admin'));
        ActivityLog::logBLConfirmed(auth()->user(), $booking);
        return redirect()->route('booking.show', $booking)->with('success', 'BL confirmed successfully.');
    }

    public function confirmPayment(Booking $booking)
    {
        $booking->invoice->update(['status' => 'Paid']);
        $booking->invoice->payment->update(['status' => 'Confirmed']);
        Mail::to($booking->user->email)->send(new PaymentVerification($booking, $booking->invoice, $booking->invoice->payment, 'confirmed'));
        return redirect()->route('booking.show', $booking)->with('success', 'Payment confirmed successfully.');
    }

    public function rejectPayment(Booking $booking)
    {
        $booking->invoice->payment->delete();
        Mail::to($booking->user->email)->send(new PaymentVerification($booking, $booking->invoice, $booking->invoice->payment, 'rejected'));
        return redirect()->route('booking.show', $booking)->with('success', 'Payment rejected successfully.');
    }

    public function sailing(Booking $booking)
    {
        $booking->update(['status' => 5]);
        Mail::to($booking->user->email)->send(new BookingStatusUpdated($booking, 'customer'));
        Mail::to(config('mail.admin_to'))->send(new BookingStatusUpdated($booking, 'admin'));
        ActivityLog::logSailing(auth()->user(), $booking);
        return redirect()->route('booking.show', $booking)->with('success', 'Sailing confirmed successfully.');
    }

    public function arrived(Booking $booking)
    {
        $booking->update(['status' => 6]);
        Mail::to($booking->user->email)->send(new BookingStatusUpdated($booking, 'customer'));
        Mail::to(config('mail.admin_to'))->send(new BookingStatusUpdated($booking, 'admin'));
        ActivityLog::logArrived(auth()->user(), $booking);
        return redirect()->route('booking.show', $booking)->with('success', 'Arrival confirmed successfully.');
    }

    public function completed(Booking $booking)
    {
        $booking->update(['status' => 7]);
        Mail::to($booking->user->email)->send(new BookingStatusUpdated($booking, 'customer'));
        Mail::to(config('mail.admin_to'))->send(new BookingStatusUpdated($booking, 'admin'));
        ActivityLog::logCompleted(auth()->user(), $booking);
        return redirect()->route('booking.show', $booking)->with('success', 'Booking completed successfully.');
    }

    public function cancel(Booking $booking)
    {
        $booking->update(['status' => 0]);
        Mail::to($booking->user->email)->send(new BookingStatusUpdated($booking, 'customer'));
        Mail::to(config('mail.admin_to'))->send(new BookingStatusUpdated($booking, 'admin'));
        ActivityLog::logBookingCancelled(auth()->user(), $booking);
        return redirect()->route('booking.show', $booking)->with('success', 'Booking cancelled successfully.');
    }

    public function uploadDocument(Request $request, Booking $booking)
    {
        try {
            $validated = $request->validate([
                'document_type' => 'required|string|in:container_load_list,notice_of_arrival,towing_certificate,vendor_invoice',
                'document_file' => 'required|file|mimes:pdf,jpeg,png,jpg|max:10240',
            ]);

            \DB::beginTransaction();

            $file = $request->file('document_file');
            
            // Generate filename using booking number and document type
            $fileName = $booking->booking_number . '_' . $validated['document_type'] . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('documents/' . $validated['document_type'], $fileName, 'public');

            // Update the booking with the document path
            $booking->update([
                $validated['document_type'] => $filePath
            ]);

            \DB::commit();

            return redirect()->route('booking.show', $booking)
                ->with('success', ucwords(str_replace('_', ' ', $validated['document_type'])) . ' uploaded successfully.');

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Document upload failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'document_type' => $request->input('document_type')
            ]);
            
            return back()->with('error', 'Error uploading document: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function downloadDocument(Booking $booking, string $type)
    {
        try {
            // Validate document type
            if (!in_array($type, ['container_load_list', 'notice_of_arrival', 'towing_certificate', 'vendor_invoice'])) {
                abort(404, 'Invalid document type');
            }

            // Check if document exists
            $documentPath = $booking->$type;
            if (!$documentPath || !Storage::disk('public')->exists($documentPath)) {
                abort(404, 'Document not found');
            }

            // Get file extension from path
            $extension = pathinfo($documentPath, PATHINFO_EXTENSION);

            // Generate download filename
            $downloadName = $booking->booking_number . '_' . str_replace('_', ' ', $type) . '.' . $extension;

            return Storage::disk('public')->download($documentPath, $downloadName);

        } catch (\Exception $e) {
            \Log::error('Document download failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'document_type' => $type
            ]);
            
            return back()->with('error', 'Error downloading document: ' . $e->getMessage());
        }
    }
}
