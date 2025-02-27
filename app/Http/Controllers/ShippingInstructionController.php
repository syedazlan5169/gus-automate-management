<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ShippingInstruction;
use Illuminate\Http\Request;
use App\Models\Cargo;

class ShippingInstructionController extends Controller
{
    /**
     * Show the form for creating a new shipping instruction.
     */
    public function create(Booking $booking)
    {
        // Get available (unallocated) cargos for this booking
        $availableCargos = $booking->cargos()
            ->whereNull('shipping_instruction_id')
            ->get()
            ->groupBy('container_type');

        return view('shipping-instructions.create', compact('booking', 'availableCargos'));
    }

    /**
     * Store a newly created shipping instruction in storage.
     */
    public function store(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'shipper' => 'required|string|max:255',
            'contact_shipper' => 'required|string|max:255',
            'consignee' => 'required|string|max:255',
            'contact_consignee' => 'required|string|max:255',
            'customer_instructions' => 'nullable|string',
            
            // Cargo allocation
            'cargo_allocations' => 'required|array',
            'cargo_allocations.*.cargo_id' => 'required|exists:cargos,id',
            'cargo_allocations.*.container_count' => 'required|integer|min:1',
        ]);

        // Create shipping instruction
        $shippingInstruction = $booking->shippingInstructions()->create([
            'shipper' => $validated['shipper'],
            'contact_shipper' => $validated['contact_shipper'],
            'consignee' => $validated['consignee'],
            'contact_consignee' => $validated['contact_consignee'],
            'customer_instructions' => $validated['customer_instructions'],
        ]);

        // Handle cargo allocations
        foreach ($validated['cargo_allocations'] as $allocation) {
            $originalCargo = $booking->cargos()->findOrFail($allocation['cargo_id']);
            
            // Validate that we're not over-allocating containers
            $totalAllocated = $booking->cargos()
                ->where('container_type', $originalCargo->container_type)
                ->whereNotNull('shipping_instruction_id')
                ->sum('container_count');
                
            if ($totalAllocated + $allocation['container_count'] > $originalCargo->container_count) {
                return back()->withErrors(['allocation' => 'Cannot allocate more containers than available']);
            }

            // Create new cargo record for this shipping instruction
            $newCargo = $booking->cargos()->create([
                'shipping_instruction_id' => $shippingInstruction->id,
                'container_type' => $originalCargo->container_type,
                'container_count' => $allocation['container_count'],
                'total_weight' => ($originalCargo->total_weight / $originalCargo->container_count) * $allocation['container_count'],
                'cargo_description' => $originalCargo->cargo_description,
            ]);

            // Move the appropriate number of containers to the new cargo
            $containersToMove = $originalCargo->containers()
                ->whereNull('container_number')
                ->limit($allocation['container_count'])
                ->get();

            foreach ($containersToMove as $container) {
                $container->update(['cargo_id' => $newCargo->id]);
            }

            // Update original cargo count
            $originalCargo->update([
                'container_count' => $originalCargo->container_count - $allocation['container_count']
            ]);

            // If original cargo has no containers left, delete it
            if ($originalCargo->container_count <= 0) {
                $originalCargo->delete();
            }
        }

        return redirect()->route('booking.show', $booking)
            ->with('success', 'Shipping instruction added successfully.');
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
}
