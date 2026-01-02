<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ShippingInstruction;
use Illuminate\Http\Request;
use App\Models\Cargo;
use Illuminate\Support\Facades\DB;
use App\Models\CargoContainer;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\UpdateSI;
use App\Models\ActivityLog;
use App\Models\Voyage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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

        return view('shipping-instructions.create', compact(
            'booking',
            'availableContainers',
        ));
    }
    
    // Store a newly created shipping instruction in storage.
    public function store(Request $request, Booking $booking)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'box_operator' => 'required|string',
                'shipper' => 'required|string',
                'shipper_contact' => 'required|string',
                'shipper_address' => 'required|array',
                'shipper_address.line1' => 'required|string',
                'shipper_address.line2' => 'nullable|string',
                'shipper_address.line3' => 'nullable|string',
                'shipper_address.line4' => 'nullable|string',
                'consignee' => 'required|string',
                'consignee_contact' => 'required|string',
                'consignee_address' => 'required|array',
                'consignee_address.line1' => 'required|string',
                'consignee_address.line2' => 'nullable|string',
                'consignee_address.line3' => 'nullable|string',
                'consignee_address.line4' => 'nullable|string',
                'notify_party' => 'required|string',
                'notify_party_contact' => 'required|string',
                'notify_party_address' => 'required|array',
                'notify_party_address.line1' => 'required|string',
                'notify_party_address.line2' => 'nullable|string',
                'notify_party_address.line3' => 'nullable|string',
                'notify_party_address.line4' => 'nullable|string',
                'cargo_description' => 'required|string',
                'hs_code' => 'nullable|string',
                'gross_weight' => 'required|numeric',
                'volume' => 'required|numeric',
                'containers' => 'required|array',
                'containers.*' => 'required|array',
                'containers.*.*.container_number' => 'required|string',
                'containers.*.*.seal_number' => 'required|string',
                'exceeding_containers' => 'nullable|string',
            ]);

            // Generate sub booking number and bl number
            $siCount = $booking->shippingInstructions()->count() + 1;
            $letter = chr(64 + $siCount); // Convert number to letter (65 is ASCII for 'A')
            $subBookingNumber = $booking->booking_number . $letter;

            // generate bl number
            $voyage = Voyage::find($booking->voyage_id);
            $voyage->last_bl_suffix += 1;
            $voyage->save();
            
            $blNumber = $voyage->voyage_number . '/' . $voyage->last_bl_suffix;

            // Create shipping instruction
            $shippingInstruction = ShippingInstruction::create([
                'booking_id' => $booking->id,
                'sub_booking_number' => $subBookingNumber,
                'bl_number' => $blNumber,
                'box_operator' => $validated['box_operator'],
                'shipper' => $validated['shipper'],
                'shipper_contact' => $validated['shipper_contact'],
                'shipper_address' => $validated['shipper_address'],
                'consignee' => $validated['consignee'],
                'consignee_contact' => $validated['consignee_contact'],
                'consignee_address' => $validated['consignee_address'],
                'notify_party' => $validated['notify_party'],
                'notify_party_contact' => $validated['notify_party_contact'],
                'notify_party_address' => $validated['notify_party_address'],
                'cargo_description' => $validated['cargo_description'],
                'hs_code' => $validated['hs_code'] ?? null,
                'gross_weight' => $validated['gross_weight'],
                'volume' => $validated['volume'],
            ]);

            // Handle exceeding containers if any
            if ($request->has('exceeding_containers')) {
                $exceedingContainers = json_decode($request->exceeding_containers, true);
                foreach ($exceedingContainers as $container) {
                    // Update cargo allocation
                    $cargo = Cargo::find($container['cargoId']);
                    if ($cargo) {
                        $cargo->container_count += $container['exceeding'];
                        $cargo->save();
                    }
                }
            }

            // Process containers
            foreach ($validated['containers'] as $cargoId => $containers) {
                // Get available empty containers for this cargo
                $availableContainers = CargoContainer::where('cargo_id', $cargoId)
                    ->whereNull('shipping_instruction_id')
                    ->take(count($containers))
                    ->get();

                if ($availableContainers->count() < count($containers)) {
                    // Create new containers for the exceeding count
                    $neededCount = count($containers) - $availableContainers->count();
                    $cargo = Cargo::find($cargoId);
                    
                    for ($i = 0; $i < $neededCount; $i++) {
                        CargoContainer::create([
                            'cargo_id' => $cargoId,
                            'shipping_instruction_id' => $shippingInstruction->id,
                            'container_number' => $containers[$availableContainers->count() + $i]['container_number'],
                            'seal_number' => $containers[$availableContainers->count() + $i]['seal_number'],
                        ]);
                    }
                }

                foreach ($containers as $index => $container) {
                    if (isset($availableContainers[$index])) {
                        // Update existing container
                        $availableContainers[$index]->update([
                            'shipping_instruction_id' => $shippingInstruction->id,
                            'container_number' => $container['container_number'],
                            'seal_number' => $container['seal_number'],
                        ]);
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
                ->with('error', 'Error creating shipping instruction: ' . $e->getMessage());
        }
    }

    // Generate Telex Bill of Lading for a shipping instruction
    public function generateTelexBL(ShippingInstruction $shippingInstruction)
    {
        try {
            // Load necessary relationships
            $shippingInstruction->load([
                'booking',
                'containers', // Load containers
                'cargos' // Load cargos to get container types
            ]);

            // Group containers by type for the BL
            $containersByType = $shippingInstruction->containers
                ->groupBy('cargo.container_type') // Group by the cargo's container type
                ->map(function ($containers) use ($shippingInstruction) {
                    return [
                        'count' => $containers->count(),
                        'total_weight' => $shippingInstruction->cargos->first()->total_weight,
                        'containers' => $containers->map(function ($container) {
                            return [
                                'container_number' => $container->container_number,
                                'seal_number' => $container->seal_number,
                            ];
                        })->values()
                    ];
                });
            
            // Generate PDF
            $pdf = PDF::loadView('shipping-instructions.telex-bill-of-lading', compact(
                'shippingInstruction',
                'containersByType'
            ));

            // Generate filename using booking number and SI number
            // Replace / and \ with -
            $blNumberSafe = str_replace(['/', '\\'], '-', $shippingInstruction->bl_number);

            // Now use the safe version
            $filename = "BL_{$blNumberSafe}.pdf";


            // Return the PDF for download
            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('BL Generation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate Bill of Lading. Please try again.');
        }
    }



    // Generate Bill of Lading for a shipping instruction
    public function generateBL(ShippingInstruction $shippingInstruction)
    {
        try {
            // Load necessary relationships
            $shippingInstruction->load([
                'booking',
                'containers', // Load containers
                'cargos' // Load cargos to get container types
            ]);

            // Group containers by type for the BL
            $containersByType = $shippingInstruction->containers
                ->groupBy('cargo.container_type') // Group by the cargo's container type
                ->map(function ($containers) use ($shippingInstruction) {
                    return [
                        'count' => $containers->count(),
                        'total_weight' => $shippingInstruction->cargos->first()->total_weight,
                        'containers' => $containers->map(function ($container) {
                            return [
                                'container_number' => $container->container_number,
                                'seal_number' => $container->seal_number,
                            ];
                        })->values()
                    ];
                });
            
            // Generate PDF
            $pdf = PDF::loadView('shipping-instructions.bill-of-lading', compact(
                'shippingInstruction',
                'containersByType'
            ));

            // Generate filename using booking number and SI number
            // Replace / and \ with -
            $blNumberSafe = str_replace(['/', '\\'], '-', $shippingInstruction->bl_number);

            // Now use the safe version
            $filename = "BL_{$blNumberSafe}.pdf";


            // Return the PDF for download
            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('BL Generation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate Bill of Lading. Please try again.');
        }
    }

    // Generate Manifest for a shipping instruction
    public function generateManifest(ShippingInstruction $shippingInstruction)
    {
        try {
            // Load necessary relationships
            $shippingInstruction->load([
                'booking',
                'containers', // Load containers
                'cargos' // Load cargos to get container types
            ]);

            // Group containers by type for the BL
            $containersByType = $shippingInstruction->containers
                ->groupBy('cargo.container_type') // Group by the cargo's container type
                ->map(function ($containers) use ($shippingInstruction) {
                    return [
                        'count' => $containers->count(),
                        'total_weight' => $shippingInstruction->cargos->first()->total_weight,
                        'containers' => $containers->map(function ($container) {
                            return [
                                'container_number' => $container->container_number,
                                'seal_number' => $container->seal_number,
                            ];
                        })->values()
                    ];
                });
            
            // Generate PDF
            $pdf = PDF::loadView('shipping-instructions.manifest', compact(
                'shippingInstruction',
                'containersByType'
            ));

            // Set PDF page size to A4 landscape
            $pdf->setPaper('a4', 'landscape');

            // Generate filename using bl number
            // Replace / and \ with -
            $blNumberSafe = str_replace(['/', '\\'], '-', $shippingInstruction->bl_number);

            // Now use the safe version
            $filename = "Manifest_{$blNumberSafe}.pdf";


            // Return the PDF for download
            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Manifest Generation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate Manifest. Please try again.');
        }
    }

    // Display the specified shipping instruction.
    public function show(ShippingInstruction $shippingInstruction)
    {
        $shippingInstruction->load(['booking', 'cargos.containers']);
        return view('shipping-instructions.show', compact('shippingInstruction'));
    }

    // Show the form for editing the specified shipping instruction.
    public function edit(ShippingInstruction $shippingInstruction)
    {
        return view('shipping-instructions.edit', compact('shippingInstruction'));
    }

    // Update the specified shipping instruction in storage.
    public function update(Request $request, ShippingInstruction $shippingInstruction)
    {
        try {
            $validated = $request->validate([
                'box_operator' => 'required|string|max:255',
                'shipper' => 'required|string|max:255',
                'shipper_contact' => 'required|string|max:255',
                'shipper_address.line1' => 'required|string|max:255',
                'shipper_address.line2' => 'nullable|string|max:255',
                'shipper_address.line3' => 'nullable|string|max:255',
                'shipper_address.line4' => 'nullable|string|max:255',
                'consignee' => 'required|string|max:255',
                'consignee_contact' => 'required|string|max:255',
                'consignee_address.line1' => 'required|string|max:255',
                'consignee_address.line2' => 'nullable|string|max:255',
                'consignee_address.line3' => 'nullable|string|max:255',
                'consignee_address.line4' => 'nullable|string|max:255',
                'notify_party' => 'required|string|max:255',
                'notify_party_contact' => 'required|string|max:255',
                'notify_party_address.line1' => 'required|string|max:255',
                'notify_party_address.line2' => 'nullable|string|max:255',
                'notify_party_address.line3' => 'nullable|string|max:255',
                'notify_party_address.line4' => 'nullable|string|max:255',
                'cargo_description' => 'required|string|max:255',
                'hs_code' => 'nullable|string|max:255',
                'gross_weight' => 'required|numeric|min:0',
                'volume' => 'nullable|numeric|min:0',
                'containers' => 'required|array',
            ]);

            DB::beginTransaction();

            // Update shipping instruction basic details
            $shippingInstruction->update([
                'box_operator' => $validated['box_operator'],
                'shipper' => $validated['shipper'],
                'shipper_contact' => $validated['shipper_contact'],
                'shipper_address' => $validated['shipper_address'],
                'consignee' => $validated['consignee'],
                'consignee_contact' => $validated['consignee_contact'],
                'consignee_address' => $validated['consignee_address'],
                'notify_party' => $validated['notify_party'],
                'notify_party_contact' => $validated['notify_party_contact'],
                'notify_party_address' => $validated['notify_party_address'],
                'cargo_description' => $validated['cargo_description'],
                'hs_code' => $validated['hs_code'] ?? null,
                'gross_weight' => $validated['gross_weight'],
                'volume' => $validated['volume'],
            ]);

            if ($shippingInstruction->booking->status >= 4 && $shippingInstruction->bl_confirmed == true) {
                $shippingInstruction->update([
                    'post_bl_edit_count' => $shippingInstruction->post_bl_edit_count + 1,
                ]);
                Mail::to(config('mail.admin_to'))->send(new UpdateSI($shippingInstruction));
            }

            // Handle containers
            if ($request->has('containers')) {
                // Track new container counts per cargo
                $cargoContainerCounts = [];

                // Get all container numbers from the request
                $requestContainers = collect($request->containers)
                    ->flatMap(function ($containerGroup) {
                        return collect($containerGroup)->pluck('container_number');
                    })
                    ->filter()
                    ->unique() // Ensure we have unique container numbers
                    ->values() // Reset array keys
                    ->toArray();

                // Get existing containers for this shipping instruction
                $existingContainers = $shippingInstruction->containers()
                    ->pluck('container_number')
                    ->unique() // Ensure we have unique container numbers
                    ->values() // Reset array keys
                    ->toArray();

                // Debug information
                \Log::info('Container comparison', [
                    'existing_containers' => $existingContainers,
                    'request_containers' => $requestContainers,
                ]);

                // Find containers that need to be released (in existing but not in request)
                $containersToRelease = array_diff($existingContainers, $requestContainers);
                
                \Log::info('Containers to release', [
                    'containers_to_release' => $containersToRelease
                ]);

                // Release containers that are no longer in the request
                if (!empty($containersToRelease)) {
                    CargoContainer::whereIn('container_number', $containersToRelease)
                        ->where('shipping_instruction_id', $shippingInstruction->id)
                        ->update(['shipping_instruction_id' => null]);
                }

                // Update or create containers
                foreach ($request->containers as $cargoId => $containerGroup) {
                    // Initialize counter for this cargo if not exists
                    if (!isset($cargoContainerCounts[$cargoId])) {
                        $cargoContainerCounts[$cargoId] = 0;
                    }

                    foreach ($containerGroup as $container) {
                        if (!isset($container['container_number']) || !isset($container['seal_number'])) {
                            continue;
                        }

                        $containerNumber = $container['container_number'];
                        
                        // Check if this container already exists in this shipping instruction
                        if (in_array($containerNumber, $existingContainers)) {
                            // Update the existing container
                            CargoContainer::where('container_number', $containerNumber)
                                ->where('shipping_instruction_id', $shippingInstruction->id)
                                ->update([
                                    'seal_number' => $container['seal_number'],
                                ]);
                        } else {
                            // This is a new container, look for an available container to reuse
                            $availableContainer = CargoContainer::where('cargo_id', $cargoId)
                                ->whereNull('shipping_instruction_id')
                                ->first();

                            if ($availableContainer) {
                                // Reuse the available container
                                $availableContainer->update([
                                    'container_number' => $containerNumber,
                                    'shipping_instruction_id' => $shippingInstruction->id,
                                    'seal_number' => $container['seal_number'],
                                ]);
                            } else {
                                // Create a new container and increment the counter
                                CargoContainer::create([
                                    'cargo_id' => $cargoId,
                                    'shipping_instruction_id' => $shippingInstruction->id,
                                    'container_number' => $containerNumber,
                                    'seal_number' => $container['seal_number'],
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

            DB::commit();

            ActivityLog::logShippingInstructionEdited(Auth::user(), $shippingInstruction);

            return redirect()
                ->route('shipping-instructions.show', $shippingInstruction)
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

            ActivityLog::logShippingInstructionDeleted(Auth::user(), $shippingInstruction);

            return redirect()->route('booking.show', $shippingInstruction->booking)
                ->with('success', 'Shipping instruction deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Shipping Instruction deletion failed: ' . $e->getMessage());
            
            return back()->with('error', 'Error deleting shipping instruction. Please try again.');
        }
    }

    public function parseShippingInstruction(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|mimes:xlsx,xls,csv',
            ]);

            if ($validator->fails()) {
                \Log::error('Shipping instruction file validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('file');
            \Log::info('Processing shipping instruction file', [
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);
            
            // Load the spreadsheet
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            \Log::info('Excel file loaded', [
                'total_rows' => count($rows),
                'first_few_rows' => array_slice($rows, 0, 15)
            ]);

            // Check if the file is a template by verifying cell values
            $expectedHeaders = [
                'B2' => 'BOX OPERATOR',
                'B4' => 'SHIPPER NAME',
                'B5' => 'SHIPPER CONTACT',
                'B6' => 'SHIPPER ADDRESS',
                'B11' => 'CONSIGNEE NAME',
                'B12' => 'CONSIGNEE CONTACT',
                'B13' => 'CONSIGNEE ADDRESS'
            ];
            
            $isTemplate = true;
            foreach ($expectedHeaders as $cell => $expectedValue) {
                $cellValue = $worksheet->getCell($cell)->getValue();
                if (empty($cellValue) || trim(strtoupper($cellValue)) !== $expectedValue) {
                    $isTemplate = false;
                    break;
                }
            }
            
            if (!$isTemplate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wrong file has been uploaded, please use the template given'
                ], 422);
            }

            // Extract shipping instruction data based on the template structure
            $shippingData = [
                'box_operator' => trim($worksheet->getCell('C2')->getValue() ?? ''),
                'shipper' => trim($worksheet->getCell('C4')->getValue() ?? ''),
                'shipper_contact' => trim($worksheet->getCell('C5')->getValue() ?? ''),
                'shipper_address_line1' => trim($worksheet->getCell('C6')->getValue() ?? ''),
                'shipper_address_line2' => trim($worksheet->getCell('C7')->getValue() ?? ''),
                'shipper_address_line3' => trim($worksheet->getCell('C8')->getValue() ?? ''),
                'shipper_address_line4' => trim($worksheet->getCell('C9')->getValue() ?? ''),
                'consignee' => trim($worksheet->getCell('C11')->getValue() ?? ''),
                'consignee_contact' => trim($worksheet->getCell('C12')->getValue() ?? ''),
                'consignee_address_line1' => trim($worksheet->getCell('C13')->getValue() ?? ''),
                'consignee_address_line2' => trim($worksheet->getCell('C14')->getValue() ?? ''),
                'consignee_address_line3' => trim($worksheet->getCell('C15')->getValue() ?? ''),
                'consignee_address_line4' => trim($worksheet->getCell('C16')->getValue() ?? ''),
                'notify_party' => trim($worksheet->getCell('F11')->getValue() ?? ''),
                'notify_party_contact' => trim($worksheet->getCell('F12')->getValue() ?? ''),
                'notify_party_address_line1' => trim($worksheet->getCell('F13')->getValue() ?? ''),
                'notify_party_address_line2' => trim($worksheet->getCell('F14')->getValue() ?? ''),
                'notify_party_address_line3' => trim($worksheet->getCell('F15')->getValue() ?? ''),
                'notify_party_address_line4' => trim($worksheet->getCell('F16')->getValue() ?? ''),
                'cargo_description' => trim($worksheet->getCell('E19')->getValue() ?? ''),
                'hs_code' => trim($worksheet->getCell('F19')->getValue() ?? ''),
                'gross_weight' => trim($worksheet->getCell('G19')->getValue() ?? ''),
                'volume' => trim($worksheet->getCell('H19')->getValue() ?? '')
            ];

            \Log::info('Extracted shipping data', $shippingData);

            // Extract container data starting from row 19
            $containers = [];
            $invalidContainers = [];
            $row = 19;
            
            while (true) {
                try {
                    $containerNumber = trim($worksheet->getCell('B' . $row)->getValue() ?? '');
                    $sealNumber = trim($worksheet->getCell('C' . $row)->getValue() ?? 'NIL');
                    $containerType = trim($worksheet->getCell('D' . $row)->getValue() ?? '');
                    
                    // Break if we find an empty container number
                    if (empty($containerNumber)) {
                        break;
                    }

                    // Validate container number format (you can adjust this regex as needed)
                    $isValid = preg_match('/^[A-Z]{4}\d{7}$/', $containerNumber);
                    
                    if (!$isValid) {
                        \Log::warning('Invalid container number format', [
                            'row' => $row,
                            'container_number' => $containerNumber
                        ]);
                        $invalidContainers[] = [
                            'row' => $row,
                            'container_number' => $containerNumber,
                            'reason' => 'Invalid container number format. Expected format: 4 uppercase letters followed by 7 digits (e.g., ABCD1234567)'
                        ];
                    }

                    // Include all containers in the list, even invalid ones, so users can edit them
                    $containers[] = [
                        'number' => $containerNumber,
                        'seal' => $sealNumber,
                        'type' => $containerType,
                        'is_invalid' => !$isValid,
                        'validation_error' => !$isValid ? 'Invalid container number format. Expected format: 4 uppercase letters followed by 7 digits (e.g., ABCD1234567)' : null
                    ];
                    
                    $row++;
                } catch (\Exception $e) {
                    \Log::error('Error processing container row ' . $row, [
                        'exception' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $row++;
                    continue; // Continue processing other rows
                }
            }

            \Log::info('Extracted containers', [
                'count' => count($containers),
                'invalid_count' => count($invalidContainers),
                'containers' => $containers
            ]);

            // Build response message
            $message = 'File processed successfully';
            if (!empty($invalidContainers)) {
                $message .= '. ' . count($invalidContainers) . ' invalid container number(s) found. Please review and edit them in the form below or upload a new file.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'shippingData' => $shippingData,
                'containers' => $containers,
                'warnings' => !empty($invalidContainers) ? [
                    'invalid_containers' => $invalidContainers
                ] : null
            ]);

        } catch (\Throwable $e) {
            \Log::error('Error parsing shipping instruction: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Always return JSON response, never HTML
            return response()->json([
                'success' => false,
                'message' => 'Error processing file: ' . $e->getMessage()
            ], 500);
        }
    }

    public function parseContainerList(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('file');
            
            // Load the spreadsheet
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Skip header row and process data
            $containers = [];
            $rowNumber = 1; // For error reporting
            
            foreach (array_slice($rows, 1) as $row) {
                $rowNumber++;
                
                // Validate row has both container number and seal number
                if (empty($row[0]) || empty($row[1])) {
                    continue;
                }

                // Validate container number format (you can adjust this regex as needed)
                if (!preg_match('/^[A-Z]{4}\d{7}$/', trim($row[0]))) {
                    return response()->json([
                        'success' => false,
                        'message' => "Invalid container number format at row {$rowNumber}: {$row[0]}"
                    ], 422);
                }

                $containers[] = [
                    'number' => trim($row[0]),
                    'seal' => trim($row[1])
                ];
            }

            if (empty($containers)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid container data found in file'
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'File processed successfully',
                'containers' => $containers
            ]);

        } catch (\Exception $e) {
            \Log::error('Error parsing container list: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing file: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadTemplate()
    {
        return response()->download(public_path('template/container_list_template.xlsx'));
    }

    public function releaseTelexBL(ShippingInstruction $shippingInstruction)
    {
        $shippingInstruction->update(['telex_bl_released' => true]);
        $shippingInstruction->booking->update(['enable_edit' => false]);
        ActivityLog::logTelexBLReleased(Auth::user(), $shippingInstruction);
        return redirect()->route('booking.show', $shippingInstruction->booking)->with('success', 'Telex BL released successfully for shipping instruction ' . $shippingInstruction->sub_booking_number . '.');
    }
}
