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
        try {
            $validated = $request->validate([
                'box_operator' => 'required|string|max:255',
                'shipper' => 'required|string|max:255',
                'contact_shipper' => 'required|string|max:255',
                'consignee' => 'required|string|max:255',
                'contact_consignee' => 'required|string|max:255',
                'notify_party' => 'required|string|max:255',
                'notify_party_contact' => 'required|string|max:255',
                'notify_party_address' => 'required|string',
                'cargo_description' => 'required|string|max:255',
                'hs_code' => 'required|string|max:255',
                'containers' => 'required|array',
                'containers.*' => 'array',
                'containers.*.*' => 'array',
                'containers.*.*.*' => 'required|string',
            ]);

            DB::beginTransaction();

            // Generate sub booking number
            $siCount = $booking->shippingInstructions()->count() + 1;
            $subBookingNumber = $booking->booking_number . '-A' . str_pad($siCount, 3, '0', STR_PAD_LEFT);

            // Create shipping instruction
            $shippingInstruction = $booking->shippingInstructions()->create([
                'sub_booking_number' => $subBookingNumber,
                'box_operator' => $validated['box_operator'],
                'shipper' => $validated['shipper'],
                'contact_shipper' => $validated['contact_shipper'],
                'consignee' => $validated['consignee'],
                'contact_consignee' => $validated['contact_consignee'],
                'notify_party' => $validated['notify_party'],
                'notify_party_contact' => $validated['notify_party_contact'],
                'notify_party_address' => $validated['notify_party_address'],
                'cargo_description' => $validated['cargo_description'],
                'hs_code' => $validated['hs_code'],
            ]);

            // Process containers
            foreach ($validated['containers'] as $cargoId => $containers) {
                foreach ($containers as $container) {
                    CargoContainer::create([
                        'cargo_id' => $cargoId,
                        'shipping_instruction_id' => $shippingInstruction->id,
                        'container_number' => $container['container_number'],
                        'seal_number' => $container['seal_number'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('booking.show', $booking)
                ->with('success', 'Shipping Instruction created successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Shipping Instruction creation failed: ' . $e->getMessage());
            return back()
                ->with('error', 'Error creating shipping instruction. Please try again.')
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
        \Log::info('Update request received', ['data' => $request->all()]);

        $validated = $request->validate([
            'box_operator' => 'required|string|max:255',
            'shipper' => 'required|string|max:255',
            'contact_shipper' => 'required|string|max:255',
            'consignee' => 'required|string|max:255',
            'contact_consignee' => 'required|string|max:255',
            'notify_party' => 'required|string|max:255',
            'notify_party_contact' => 'required|string|max:255',
            'notify_party_address' => 'required|string',
            'cargo_description' => 'required|string|max:255',
            'hs_code' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            
            \Log::info('Updating SI', ['validated' => $validated]);
            
            // Update shipping instruction details
            $shippingInstruction->update($validated);

            // Handle containers
            if ($request->has('containers')) {
                \Log::info('Processing containers', ['containers' => $request->containers]);

                // Get all container numbers from the request
                $requestContainers = [];
                foreach ($request->containers as $cargoId => $containerGroup) {
                    foreach ($containerGroup as $container) {
                        if (isset($container['container_number'])) {
                            $requestContainers[] = $container['container_number'];
                        }
                    }
                }

                // Delete containers that are no longer in the request
                $shippingInstruction->containers()
                    ->whereNotIn('container_number', $requestContainers)
                    ->delete();

                // Update or create containers
                foreach ($request->containers as $cargoId => $containerGroup) {
                    foreach ($containerGroup as $container) {
                        if (!isset($container['container_number']) || !isset($container['seal_number'])) {
                            continue;
                        }

                        \Log::info('Processing container', ['container' => $container]);

                        CargoContainer::updateOrCreate(
                            [
                                'shipping_instruction_id' => $shippingInstruction->id,
                                'container_number' => $container['container_number'],
                            ],
                            [
                                'cargo_id' => $cargoId,
                                'seal_number' => $container['seal_number'],
                            ]
                        );
                    }
                }
            }
            
            DB::commit();

            return redirect()->route('shipping-instructions.show', $shippingInstruction)
                ->with('success', 'Shipping instruction updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Shipping Instruction update failed: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return back()
                ->with('error', 'Error updating shipping instruction: ' . $e->getMessage())
                ->withInput();
        }
    }

    
    public function destroy(ShippingInstruction $shippingInstruction)
    {
        try {
            DB::beginTransaction();

            // Release all containers associated with this SI by setting shipping_instruction_id to null
            $shippingInstruction->containers()->update(['shipping_instruction_id' => null]);

            // Delete the shipping instruction
            $shippingInstruction->delete();

            DB::commit();

            return redirect()->route('booking.show', $shippingInstruction->booking)
                ->with('success', 'Shipping instruction deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Shipping Instruction deletion failed: ' . $e->getMessage());
            
            return back()->with('error', 'Error deleting shipping instruction. Please try again.');
        }
    }
}
