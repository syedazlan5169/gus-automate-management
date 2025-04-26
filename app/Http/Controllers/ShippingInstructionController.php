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
                // Get available empty containers for this cargo
                $availableContainers = CargoContainer::where('cargo_id', $cargoId)
                    ->whereNull('shipping_instruction_id')
                    ->take(count($containers))
                    ->get();

                foreach ($containers as $index => $container) {
                    if (isset($availableContainers[$index])) {
                        // Update existing container
                        $availableContainers[$index]->update([
                            'shipping_instruction_id' => $shippingInstruction->id,
                            'container_number' => $container['container_number'],
                            'seal_number' => $container['seal_number'],
                        ]);
                    } else {
                        // Log error if we run out of available containers
                        \Log::error("No available container found for cargo ID: {$cargoId}");
                        throw new \Exception('Insufficient available containers for the cargo.');
                    }
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
                        'containers' => $containers
                            ->pluck('container_number')
                            ->values()
                    ];
                });

            // Generate PDF
            $pdf = PDF::loadView('shipping-instructions.bill-of-lading', compact(
                'shippingInstruction',
                'containersByType'
            ));

            // Generate filename using booking number and SI number
            $filename = "BL_{$shippingInstruction->sub_booking_number}.pdf";

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

            // Group containers by type for the manifest
            $containersByType = $shippingInstruction->containers
                ->groupBy('cargo.container_type') // Group by the cargo's container type
                ->map(function ($containers) use ($shippingInstruction) {
                    return [
                        'count' => $containers->count(),
                        'total_weight' => $shippingInstruction->cargos->first()->total_weight,
                        'containers' => $containers
                            ->pluck('container_number')
                            ->values()
                    ];
                });

            // Generate PDF
            $pdf = PDF::loadView('shipping-instructions.manifest', compact(
                'shippingInstruction',
                'containersByType'
            ));

            // Generate filename using booking number and SI number
            $filename = "Manifest_{$shippingInstruction->sub_booking_number}.pdf";

            // Return the PDF for download
            return $pdf->download($filename);
        } catch (\Exception $e) {
            \Log::error('Manifest generation failed: ' . $e->getMessage());
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
                'contact_shipper' => 'required|string|max:255',
                'consignee' => 'required|string|max:255',
                'contact_consignee' => 'required|string|max:255',
                'notify_party' => 'required|string|max:255',
                'notify_party_contact' => 'required|string|max:255',
                'notify_party_address' => 'required|string',
                'cargo_description' => 'required|string|max:255',
                'hs_code' => 'required|string|max:255',
                'containers' => 'required|array',
            ]);

            DB::beginTransaction();

            // Update shipping instruction basic details
            $shippingInstruction->update([
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

            if ($shippingInstruction->booking->status >= 4 && $shippingInstruction->bl_confirmed == true) {
                $shippingInstruction->update([
                    'post_bl_edit_count' => $shippingInstruction->post_bl_edit_count + 1,
                ]);
                Mail::to($shippingInstruction->booking->user->email)->send(new UpdateSI($shippingInstruction));
                Mail::to(env('MAIL_TO_ADDRESS'))->send(new UpdateSI($shippingInstruction));
            }

            // Handle containers
            if ($request->has('containers')) {
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
                                // Only create a new container if no existing container can be reused
                                CargoContainer::create([
                                    'cargo_id' => $cargoId,
                                    'shipping_instruction_id' => $shippingInstruction->id,
                                    'container_number' => $containerNumber,
                                    'seal_number' => $container['seal_number'],
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();

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
                'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
            ]);

            if ($validator->fails()) {
                \Log::error('Shipping instruction file validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
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
                0 => 'Box Operator',
                1 => 'Shipper Name',
                2 => 'Shipper Contact',
                3 => 'Consignee Name',
                4 => 'Consignee Contact',
                5 => 'Notify Party Name',
                6 => 'Notify Party Contact',
                7 => 'Notify Party Address',
                8 => 'Cargo Description',
                9 => 'HS Code'
            ];
            
            $isTemplate = true;
            foreach ($expectedHeaders as $rowIndex => $expectedValue) {
                if (!isset($rows[$rowIndex][0]) || trim($rows[$rowIndex][0]) !== $expectedValue) {
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
                'box_operator' => trim($rows[0][1] ?? ''),
                'shipper' => trim($rows[1][1] ?? ''),
                'contact_shipper' => trim($rows[2][1] ?? ''),
                'consignee' => trim($rows[3][1] ?? ''),
                'contact_consignee' => trim($rows[4][1] ?? ''),
                'notify_party' => trim($rows[5][1] ?? ''),
                'notify_party_contact' => trim($rows[6][1] ?? ''),
                'notify_party_address' => trim($rows[7][1] ?? ''),
                'cargo_description' => trim($rows[8][1] ?? ''),
                'hs_code' => trim($rows[9][1] ?? ''),
            ];

            \Log::info('Extracted shipping data', $shippingData);

            // Extract container data starting from row 12
            $containers = [];
            for ($i = 12; $i < count($rows); $i++) {
                $containerNumber = trim($rows[$i][0] ?? '');
                $sealNumber = trim($rows[$i][1] ?? '');
                $containerType = trim($rows[$i][2] ?? '20GP'); // Get container type from column 3
                
                // Skip empty rows
                if (empty($containerNumber) || empty($sealNumber)) {
                    continue;
                }

                // Validate container number format (you can adjust this regex as needed)
                if (!preg_match('/^[A-Z]{4}\d{7}$/', $containerNumber)) {
                    \Log::warning('Invalid container number format', [
                        'row' => $i + 1,
                        'container_number' => $containerNumber
                    ]);
                    continue;
                }

                $containers[] = [
                    'number' => $containerNumber,
                    'seal' => $sealNumber,
                    'type' => $containerType
                ];
            }

            \Log::info('Extracted containers', [
                'count' => count($containers),
                'containers' => $containers
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File processed successfully',
                'shippingData' => $shippingData,
                'containers' => $containers
            ]);

        } catch (\Exception $e) {
            \Log::error('Error parsing shipping instruction: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
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
                'container_type' => 'required'
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
}
