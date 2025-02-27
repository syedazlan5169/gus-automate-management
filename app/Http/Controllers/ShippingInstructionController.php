<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ShippingInstruction;
use Illuminate\Http\Request;
use App\Models\Cargo;
use Illuminate\Support\Facades\DB;

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
        // Start a database transaction
        DB::beginTransaction();
        
        try {
            $validated = $request->validate([
                'shipper' => 'required|string|max:255',
                'contact_shipper' => 'required|string|max:255',
                'consignee' => 'required|string|max:255',
                'contact_consignee' => 'required|string|max:255',
                'customer_instructions' => 'nullable|string',
                'cargo_allocations' => 'required|array',
                'cargo_allocations.*.cargo_id' => 'required|exists:cargos,id',
                'cargo_allocations.*.container_count' => 'required|integer|min:0',
            ]);

            // Create the shipping instruction
            $shippingInstruction = $booking->shippingInstructions()->create([
                'shipper' => $validated['shipper'],
                'contact_shipper' => $validated['contact_shipper'],
                'consignee' => $validated['consignee'],
                'contact_consignee' => $validated['contact_consignee'],
                'customer_instructions' => $validated['customer_instructions'],
            ]);

            // Process cargo allocations
            foreach ($validated['cargo_allocations'] as $allocation) {
                $cargo = Cargo::findOrFail($allocation['cargo_id']);
                
                // Check if allocation is greater than 0
                if ($allocation['container_count'] > 0) {
                    // Verify available containers
                    $availableContainers = $cargo->container_count - $cargo->allocatedContainers()->count();
                    
                    if ($allocation['container_count'] > $availableContainers) {
                        throw new \Exception("Cannot allocate more containers than available for cargo ID {$cargo->id}");
                    }

                    // Create new cargo for this shipping instruction
                    $newCargo = $cargo->replicate();
                    $newCargo->container_count = $allocation['container_count'];
                    $newCargo->shipping_instruction_id = $shippingInstruction->id;
                    $newCargo->save();

                    // Move the containers to the new cargo
                    $containersToMove = $cargo->containers()
                        ->whereNull('shipping_instruction_id')
                        ->limit($allocation['container_count'])
                        ->get();

                    foreach ($containersToMove as $container) {
                        $container->update([
                            'cargo_id' => $newCargo->id,
                            'shipping_instruction_id' => $shippingInstruction->id
                        ]);
                    }

                    // Update original cargo container count
                    $cargo->container_count = $availableContainers - $allocation['container_count'];
                    if ($cargo->container_count == 0) {
                        $cargo->delete(); // Delete the original cargo if no containers left
                    } else {
                        $cargo->save();
                    }
                }
            }

            DB::commit();
            return redirect()->route('booking.show', $booking)
                ->with('success', 'Shipping instruction created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
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
}
