<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ShippingInstruction;
use Illuminate\Http\Request;
use App\Models\Cargo;
use Illuminate\Support\Facades\DB;
use App\Models\CargoContainer;

class ShippingInstructionController extends Controller
{
    /**
     * Show the form for creating a new shipping instruction.
     */
    public function create(Booking $booking)
    {
        // Load the booking with its cargos and containers
        $booking->load(['cargos.containers' => function ($query) {
            // Only get containers that haven't been assigned to any SI
            $query->whereNull('shipping_instruction_id');
        }]);

        // Group available containers by cargo type
        $availableContainers = $booking->cargos->map(function ($cargo) {
            return [
                'cargo_id' => $cargo->id,
                'container_type' => $cargo->container_type,
                'total_count' => $cargo->container_count,
                'available_count' => $cargo->containers->whereNull('shipping_instruction_id')->count(),
                'containers' => $cargo->containers->whereNull('shipping_instruction_id')
            ];
        })->filter(function ($cargo) {
            return $cargo['available_count'] > 0;
        });

        // Generate sub booking number
        $siCount = $booking->shippingInstructions()->count() + 1;
        $subBookingNumber = $booking->booking_number . '-SI' . str_pad($siCount, 3, '0', STR_PAD_LEFT);

        return view('shipping-instructions.create', compact(
            'booking',
            'availableContainers',
            'subBookingNumber'
        ));
    }
    public function createold(Booking $booking)
    {
        // Get available (unallocated) cargos for this booking
        $availableCargos = $booking->cargos()
            ->whereNull('shipping_instruction_id')
            ->get()
            ->groupBy('container_type');

        return view('shipping-instructions.createold', compact('booking', 'availableCargos'));
    }
    /**
     * Store a newly created shipping instruction in storage.
     */
    public function store(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'sub_booking_number' => 'required|string|unique:shipping_instructions',
            'box_operator' => 'nullable|string|max:255',
            'shipper' => 'required|string|max:255',
            'contact_shipper' => 'required|string|max:255',
            'consignee' => 'required|string|max:255',
            'contact_consignee' => 'required|string|max:255',
            'customer_instructions' => 'nullable|string',
            'cargo_description' => 'required|string|max:255',
            'hs_code' => 'required|string|max:255',
            // Validate container selections
            'containers' => 'required|array',
            'containers.*' => 'exists:cargo_containers,id,shipping_instruction_id,NULL'
        ]);

        try {
            DB::beginTransaction();

            // Create shipping instruction
            $shippingInstruction = $booking->shippingInstructions()->create($validated);

            // Assign selected containers to this SI
            if (!empty($validated['containers'])) {
                CargoContainer::whereIn('id', $validated['containers'])
                    ->update(['shipping_instruction_id' => $shippingInstruction->id]);
            }

            DB::commit();

            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Shipping Instruction created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating shipping instruction: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Generate Bill of Lading for a shipping instruction
     */
    public function generateBL(ShippingInstruction $shippingInstruction)
    {
        // Load necessary relationships
        $shippingInstruction->load([
            'booking',
            'cargos.containers',
        ]);

        // Group containers by type for the BL
        $containersByType = $shippingInstruction->cargos
            ->groupBy('container_type')
            ->map(function ($cargos) {
                return [
                    'count' => $cargos->sum('container_count'),
                    'total_weight' => $cargos->sum('total_weight'),
                    'containers' => $cargos->flatMap->containers
                        ->pluck('container_number')
                        ->filter()
                        ->values()
                ];
            });

        // You might want to use a PDF generation library here
        // For example: barryvdh/laravel-dompdf
        return view('shipping-instructions.bill-of-lading', compact(
            'shippingInstruction',
            'containersByType'
        ));
    }

    /**
     * Display the specified shipping instruction.
     */
    public function show(ShippingInstruction $shippingInstruction)
    {
        $shippingInstruction->load(['booking', 'cargos.containers']);
        return view('shipping-instructions.show', compact('shippingInstruction'));
    }

    /**
     * Show the form for editing the specified shipping instruction.
     */
    public function edit(ShippingInstruction $shippingInstruction)
    {
        return view('shipping-instructions.edit', compact('shippingInstruction'));
    }

    /**
     * Update the specified shipping instruction in storage.
     */
    public function update(Request $request, ShippingInstruction $shippingInstruction)
    {
        $validated = $request->validate([
            'shipper' => 'required|string|max:255',
            'contact_shipper' => 'required|string|max:255',
            'consignee' => 'required|string|max:255',
            'contact_consignee' => 'required|string|max:255',
            'customer_instructions' => 'nullable|string',
        ]);

        $shippingInstruction->update($validated);

        return redirect()->route('shipping-instructions.show', $shippingInstruction)
            ->with('success', 'Shipping instruction updated successfully.');
    }

    public function parseContainers(Request $request)
    {
        $request->validate([
            'container_type' => 'required|exists:cargos,id',
            'quantity' => 'required|integer|min:1',
            'container_file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            // Parse your Excel/CSV file here
            // Return the container data in the format:
            // [{ number: "CONT123456", seal: "SEAL123" }, ...]
            
            return response()->json([
                'success' => true,
                'containers' => $containers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
