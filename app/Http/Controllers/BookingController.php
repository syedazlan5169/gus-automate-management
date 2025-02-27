<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the bookings.
     */
    public function index()
    {
        $bookings = Booking::all();
        return view('booking.index', compact('bookings'));
    }

    public function clientIndex()
    {
        $bookings = Booking::where('user_id', auth()->id())->get();
        return view('booking.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create()
    {
        return view('bookings.create');
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Service Information
            'service' => 'required|string|in:SOC,COC',
            
            // Shipping Details
            'vessel' => 'required|string|max:255',
            'voyage' => 'required|string|max:255',
            'liner_address' => 'required|string|max:255',
            
            // Route Information
            'place_of_receipt' => 'required|string|max:255',
            'pol' => 'required|string|max:255',
            'pod' => 'required|string|max:255',
            'place_of_delivery' => 'required|string|max:255',
            
            // Schedule
            'ets' => 'required|date',
            'eta' => 'required|date',
            
            // Cargo Details (arrays since multiple containers can be added)
            'container_type' => 'required|array',
            'container_type.*' => 'required|string|in:20GP,40GP,40HC,20RF,40RF',
            'container_count' => 'required|array',
            'container_count.*' => 'required|integer|min:1',
            'total_weight' => 'required|array',
            'total_weight.*' => 'required|numeric|min:0',
        ]);

        // Generate a unique booking number
        $bookingNumber = 'BK' . date('Ymd') . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);

        // Create the booking with available fields
        $booking = Booking::create([
            'booking_number' => $bookingNumber,
            'booking_date' => now(),
            'service' => $validated['service'],
            'vessel' => $validated['vessel'],
            'voyage' => $validated['voyage'],
            'liner_address' => $validated['liner_address'],
            'place_of_receipt' => $validated['place_of_receipt'],
            'pol' => $validated['pol'],
            'pod' => $validated['pod'],
            'place_of_delivery' => $validated['place_of_delivery'],
            'ets' => $validated['ets'],
            'eta' => $validated['eta'],
            'status' => 'New',
            'user_id' => auth()->id(),
        ]);

        // Store initial cargo details (unassigned to any shipping instruction)
        foreach ($validated['container_type'] as $index => $containerType) {
            // Create cargo record
            $cargo = $booking->cargos()->create([
                'container_type' => $containerType,
                'container_count' => $validated['container_count'][$index],
                'total_weight' => $validated['total_weight'][$index],
                'cargo_description' => '', // Empty for now
                'shipping_instruction_id' => null // Will be assigned later
            ]);

            // Create placeholder container records
            for ($i = 0; $i < $validated['container_count'][$index]; $i++) {
                $cargo->containers()->create([
                    'container_number' => null,
                    'seal_number' => null,
                ]);
            }
        }

        return redirect()->route('client.bookings.index')
            ->with('success', 'Booking created successfully. Please add shipping instructions.');
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        return view('booking.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function edit(Booking $booking)
    {
        return view('booking.edit', compact('booking'));
    }

    /**
     * Update the specified booking in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'booking_date' => 'required|date',
            'status' => 'required|string|in:pending,confirmed,cancelled',
            // Add other validation rules as needed
        ]);

        $booking->update($validated);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }

   
}
