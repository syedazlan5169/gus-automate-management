<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the bookings.
     */
    public function adminBookingIndex()
    {
        $bookings = Booking::paginate(10);
        return view('booking.index', compact('bookings'));
    }

    public function clientBookingIndex()
    {
        $bookings = Booking::where('user_id', auth()->id())->paginate(10);
        return view('booking.index', compact('bookings'));
    }

    // Show the form for creating a new booking.
    public function create()
    {
        return view('bookings.create');
    }

    // Store a newly created booking in storage.
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Service Information
            'service' => 'required|string|in:SOC,COC',
            
            // Shipping Details
            'vessel' => 'required|string|max:255',
            'voyage' => 'required|string|max:255',
            
            // Route Information
            'place_of_receipt' => 'required|string|max:255',
            'pol' => 'required|string|max:255',
            'pod' => 'required|string|max:255',
            'place_of_delivery' => 'required|string|max:255',
            
            // Schedule
            'ets' => 'required|date',
            'eta' => 'required|date',
            
            // Cargo Details
            'container_type' => 'required|array',
            'container_type.*' => 'required|string|in:20GP,40GP,40HC,20RF,40RF',
            'container_count' => 'required|array',
            'container_count.*' => 'required|integer|min:1',
            'total_weight' => 'required|array',
            'total_weight.*' => 'required|numeric|min:0',
        ]);

        try {
            \DB::beginTransaction();

            // Generate a unique booking number
            $bookingNumber = 'GUS' . date('Ymd') . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);

            // Create the booking
            $booking = Booking::create([
                'booking_number' => $bookingNumber,
                'booking_date' => now(),
                'service' => $validated['service'],
                'vessel' => $validated['vessel'],
                'voyage' => $validated['voyage'],
                'place_of_receipt' => $validated['place_of_receipt'],
                'pol' => $validated['pol'],
                'pod' => $validated['pod'],
                'place_of_delivery' => $validated['place_of_delivery'],
                'ets' => $validated['ets'],
                'eta' => $validated['eta'],
                'user_id' => auth()->id(),
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
        $booking->load(['cargos.containers', 'shippingInstructions.containers']);
        return view('booking.show', compact('booking'));
    }

    // Show the form for editing the specified booking.
    public function edit(Booking $booking)
    {
        return view('booking.edit', compact('booking'));
    }

    // Shipping Instructions Submission
    public function submitSI(Booking $booking)
    {
        $booking->update(['status' => 'Pending Invoice']);
        return redirect()->route('booking.show', $booking)->with('success', 'Shipping Instructions submitted successfully.');
    }

    // Invoice Submission
    public function submitInvoice(Request $request, Booking $booking)
    {
        try {
            $validated = $request->validate([
                'invoice_file' => 'required|file|mimes:pdf',
                'invoice_date' => 'required|date',
                'invoice_number' => 'required|string',
                'invoice_amount' => 'required|numeric',
                'payment_terms' => 'required|string|in:cash,credit',
            ]);

            \DB::beginTransaction();

            $invoice = $request->file('invoice_file');
            
            // Generate filename using booking number and timestamp
            $fileName = $booking->booking_number . '_' . date('Ymd_His') . '_invoice.' . $invoice->getClientOriginalExtension();
            $invoicePath = $invoice->storeAs('invoices', $fileName, 'public');

            // Log before creating invoice
            \Log::info('Attempting to create invoice', [
                'booking_id' => $booking->id,
                'invoice_path' => $invoicePath,
                'file_name' => $fileName,
                'invoice_data' => $validated
            ]);

            $invoice = Invoice::create([
                'booking_id' => $booking->id,
                'invoice_file' => $invoicePath,
                'invoice_date' => $validated['invoice_date'],
                'invoice_number' => $validated['invoice_number'],
                'invoice_amount' => $validated['invoice_amount'],
                'payment_terms' => $validated['payment_terms'],
            ]);

            // Log after invoice creation
            \Log::info('Invoice created successfully', [
                'invoice_id' => $invoice->id,
                'booking_id' => $booking->id
            ]);
            
            $booking->update(['status' => 'Pending Payment']);

            \DB::commit();

            return redirect()->route('booking.show', $booking)
                ->with('success', 'Invoice submitted successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Invoice validation failed', [
                'booking_id' => $booking->id,
                'errors' => $e->errors()
            ]);
            return back()->withErrors($e->errors())
                        ->withInput();

        } catch (\Exception $e) {
            \DB::rollBack();
            
            // Log the detailed error
            \Log::error('Failed to submit invoice', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // If file was uploaded, attempt to remove it
            if (isset($invoicePath) && \Storage::disk('public')->exists($invoicePath)) {
                try {
                    \Storage::disk('public')->delete($invoicePath);
                    \Log::info('Cleaned up uploaded file', ['path' => $invoicePath]);
                } catch (\Exception $deleteError) {
                    \Log::error('Failed to delete uploaded file', [
                        'path' => $invoicePath,
                        'error' => $deleteError->getMessage()
                    ]);
                }
            }

            return back()->with('error', 'Error submitting invoice: ' . $e->getMessage())
                        ->withInput();
        }
    }

    // Update the specified booking in storage.
    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'service' => 'sometimes|required|string|in:SOC,COC',
            'vessel' => 'sometimes|required|string|max:255',
            'voyage' => 'sometimes|required|string|max:255',
            'place_of_receipt' => 'sometimes|required|string|max:255',
            'pol' => 'sometimes|required|string|max:255',
            'pod' => 'sometimes|required|string|max:255',
            'place_of_delivery' => 'sometimes|required|string|max:255',
            'ets' => 'sometimes|required|date',
            'eta' => 'sometimes|required|date',
            'status' => 'sometimes|required|string|in:New,Pending,Confirmed,Shipped,Completed,Cancelled',
        ]);

        try {
            $booking->update($validated);
            return redirect()->route('bookings.index')
                ->with('success', 'Booking updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating booking: ' . $e->getMessage());
        }
    }

    // Remove the specified booking from storage.
    public function destroy(Booking $booking)
    {
        if (!in_array($booking->status, ['New', 'Cancelled'])) {
            return back()->with('error', 'Only new or cancelled bookings can be deleted.');
        }

        try {
            $booking->delete();
            return redirect()->route('bookings.index')
                ->with('success', 'Booking deleted successfully.');
        } catch (\Exception $e) {
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
}
