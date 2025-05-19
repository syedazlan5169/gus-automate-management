<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{ Breadcrumbs::render('booking.show', $booking) }}

            <!-- Status Progress Bar -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <h4 class="sr-only">Status</h4>
                        <div class="mt-6" aria-hidden="true">
                            <div class="overflow-hidden rounded-full bg-gray-200">
                                @php
                                    // Calculate progress percentage based on status
                                    $totalSteps = 7; // Total number of steps (excluding CANCELLED)
                                    $currentStep = 0;
                                    
                                    // Map status to step number
                                    switch($booking->status) {
                                        case $status::NEW:
                                            $currentStep = 1;
                                            break;
                                        case $status::BOOKING_CONFIRMED:
                                            $currentStep = 2;
                                            break;
                                        case $status::BL_VERIFICATION:
                                            $currentStep = 3;
                                            break;
                                        case $status::BL_CONFIRMED:
                                            $currentStep = 4;
                                            break;
                                        case $status::SAILING:
                                            $currentStep = 5;
                                            break;
                                        case $status::ARRIVED:
                                            $currentStep = 6;
                                            break;
                                        case $status::COMPLETED:
                                            $currentStep = 7;
                                            break;
                                        case $status::CANCELLED:
                                            $currentStep = 0; // Cancelled is not part of the progress
                                            break;
                                        default:
                                            $currentStep = 0;
                                    }
                                    
                                    // Calculate percentage (0% for cancelled, 100% for completed)
                                    $progressPercentage = $booking->status == $status::CANCELLED ? 0 : ($currentStep / $totalSteps) * 100;
                                    
                                    // Determine color based on status
                                    $progressColor = $booking->status == $status::CANCELLED ? 'bg-red-600' : 'bg-indigo-600';
                                @endphp
                                <div class="h-2 rounded-full {{ $progressColor }}" style="width: {{ $progressPercentage }}%"></div>
                            </div>
                            <div class="mt-6 hidden grid-cols-7 gap-4 text-sm font-medium text-gray-600 sm:grid">
                                <div class="text-center {{ $booking->status >= $status::NEW ? 'text-indigo-600' : 'text-gray-400' }}">Booking<br>Created</div>
                                <div class="text-center {{ $booking->status >= $status::BOOKING_CONFIRMED ? 'text-indigo-600' : 'text-gray-400' }}">Booking<br>Confirmed</div>
                                <div class="text-center {{ $booking->status >= $status::BL_VERIFICATION ? 'text-indigo-600' : 'text-gray-400' }}">BL<br>Verification</div>
                                <div class="text-center {{ $booking->status >= $status::BL_CONFIRMED ? 'text-indigo-600' : 'text-gray-400' }}">BL<br>Confirmed</div>
                                <div class="text-center {{ $booking->status >= $status::SAILING ? 'text-indigo-600' : 'text-gray-400' }}">Sailing</div>
                                <div class="text-center {{ $booking->status >= $status::ARRIVED ? 'text-indigo-600' : 'text-gray-400' }}">Arrived</div>
                                <div class="text-center {{ $booking->status >= $status::COMPLETED ? 'text-indigo-600' : 'text-gray-400' }}">Completed</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <!-- This should be the current status of the booking -->
                @if ($booking->status == $status::NEW && $booking->sub_status == 0)
                    @if(auth()->user()->role == 'customer')
                    <x-alert-instruction 
                        message="Please confirm the booking details and submit the booking"
                        color="green"
                    />
                    @endif
                @elseif ($booking->status == $status::NEW && $booking->sub_status == 1)
                    @if(auth()->user()->role == 'customer')
                    <x-alert-instruction 
                        message="A booking has been created, please wait for the Liner to confirm the booking"
                        color="green"
                    />
                    @else
                    <x-alert-instruction 
                        message="A new booking has been created, please update the booking details and confirm the booking"
                        action_url="{{ route('booking.edit', $booking) }}"
                        action_text="Update Booking"
                    />
                    @endif
                @elseif ($booking->status == $status::BOOKING_CONFIRMED)
                    @if(auth()->user()->role == 'customer')
                    <x-alert-instruction 
                        message="Booking has been confirmed, please add the Shipping Instructions to allow the system to generate the Bill of Lading"
                    />
                    @else
                    <x-alert-instruction 
                        message="Booking has been confirmed, waiting for customer to update the Shipping Instructions"
                        color="green"
                    />
                    @endif
                @elseif ($booking->status == $status::BL_VERIFICATION)
                    @if(auth()->user()->role == 'customer')
                    <x-alert-instruction 
                        message="Please view the Bill of Lading and confirm the BL if everything is correct."
                    />
                    @else
                    <x-alert-instruction 
                        message="BL has been generated, waiting for customer to verify and confirm the BL"
                        color="green"
                    />
                    @endif
                @elseif ($booking->status == $status::BL_CONFIRMED)
                    @if(auth()->user()->role == 'customer')
                    <x-alert-instruction 
                        message="BL has been confirmed, waiting for Liner to prepare all the documents"
                        color="green"
                    />
                    @else
                    <x-alert-instruction 
                        message="BL has been confirmed, please prepare all the documents required for the shipment"
                    />
                    @endif
                @elseif ($booking->status == $status::SAILING)
                    @if(auth()->user()->role == 'customer')
                    <x-alert-instruction 
                        message="Your shipment has sailed, please check the arrival date and time"
                        color="green"
                    />
                    @else
                    <x-alert-instruction 
                        message="This shipment has sailed, please check the arrival date and time and upload the notice of arrival once it arrived."
                        color="green"
                    />
                    @endif
                @elseif ($booking->status == $status::ARRIVED)
                    @if(auth()->user()->role == 'customer')
                    <x-alert-instruction 
                        message="Your shipment has arrived at the destination port"
                        color="green"
                    />
                    @else
                    <x-alert-instruction 
                        message="This shipment has arrived at the destination port. The complete button will be enabled once all the below conditions are met:"
                        color="green"
                    />
                    @endif
                @endif

                <div class="mt-4">
                    <!--Staff Instructions -->
                    @if($booking->status == 4 && auth()->user()->role != 'customer' && !$booking->shippingInstructions->every(function($si) { return $si->telex_bl_released; }))
                    <x-alert-instruction
                        message="Please release the Telex BL for all the shipping instructions"
                        color="red"
                    />
                    @endif
                    @if($booking->status == 6 && auth()->user()->role != 'customer' && !$booking->invoices->first())
                    <x-alert-instruction
                        message="Please upload all Invoice related to this booking"
                        color="red"
                    />
                    @endif
                    @if($booking->status == 6 && auth()->user()->role != 'customer' && !$booking->invoices->every(function($invoice) { return $invoice->status == 'Paid'; }))
                    <x-alert-instruction
                        message="Please upload payment slip for all the invoices"
                        color="red"
                    />
                    @endif
                    @if($booking->status == 6 && auth()->user()->role != 'customer' && !$booking->relatedDocuments->where('document_name', 'Manifest')->first())
                    <x-alert-instruction 
                        message="Please upload the Manifest"
                        color="red"
                    />
                    @endif
                    @if($booking->status == 6 && auth()->user()->role != 'customer' && !$booking->relatedDocuments->where('document_name', 'Container Load List')->first())
                    <x-alert-instruction 
                        message="Please upload the Container Load List"
                        color="red"
                    />
                    @endif
                    @if($booking->status == 6 && auth()->user()->role != 'customer' && !$booking->relatedDocuments->where('document_name', 'Towing Certificate')->first())
                    <x-alert-instruction 
                        message="Please upload the Towing Certificate"
                        color="red"
                    />
                    @endif
                    @if($booking->status == 6 && auth()->user()->role != 'customer' && (!$booking->relatedDocuments->where('document_name', 'Vendor Invoice CVS')->first() ||
                        !$booking->relatedDocuments->where('document_name', 'Vendor Invoice MRN')->first() ||
                        !$booking->relatedDocuments->where('document_name', 'Vendor Invoice Northsea')->first()))
                    <x-alert-instruction 
                        message="Please upload all Vendor Invoice (CVS, MRN, Northsea)"
                        color="red"
                    />
                    @endif
                    @if($booking->status == 5 && auth()->user()->role != 'customer' && !$booking->relatedDocuments->where('document_name', 'Container Load List')->first())
                    <x-alert-instruction 
                        message="Please upload the Container Load List to mark the booking as arrived"
                        color="red"
                    />
                    @endif

                    <!-- Warning Message -->
                    @if (session('warning'))
                        <x-alert-warning :message="session('warning')" />
                    @endif

                    <!-- Success Message -->
                    @if (session('success'))
                        <x-alert-success :message="session('success')" />
                    @endif
                </div>
            </div>

            <!-- Booking Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">

                    <!-- Header with Booking Number and Status -->
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <div class="flex items-center gap-3">
                                <h2 class="text-2xl font-semibold">Booking Details</h2>
                                @if ($booking->status == $status::NEW && $booking->sub_status == 0)
                                    <x-status-badge text="Draft" color="yellow"/>
                                @else
                                    <x-status-badge text="{{ $statusLabel }}" color="{{ $booking->status == $status::CANCELLED ? 'red' : 'green' }}"/>
                                @endif
                            </div>
                            <p class="text-gray-600">{{ $booking->booking_number }}</p>
                        </div>
                        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                            @if($booking->status < 2 && $booking->status > 0)
                            <button type="button"
                                onclick="window.location.href='{{ route('booking.edit', $booking) }}'"
                                class="inline-flex items-center gap-x-1.5 rounded-md bg-blue-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 uppercase tracking-widest">
                                <svg class="-ml-0.5 size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                                    data-slot="icon">
                                    <path
                                        d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                    <path
                                        d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                </svg>
                                Edit
                            </button>
                            @endif
                            @if($booking->status < 5 && $booking->status > 0)
                            <a href="#"
                                id="cancel-booking-button"
                                onclick="document.getElementById('booking-cancellation-modal').classList.remove('hidden')"
                                class="inline-flex items-center gap-x-1.5 rounded-md bg-red-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 uppercase tracking-widest">
                                <svg class="-ml-0.5 size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                                    data-slot="icon">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                                Cancel Booking
                            </a>
                            @endif
                            @if($booking->status == 0)
                                <button 
                                    type="button"
                                    class="inline-flex items-center gap-x-1.5 rounded-md bg-red-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 uppercase tracking-widest"
                                    id="delete-booking-button"
                                    onclick="document.getElementById('booking-deletion-modal').classList.remove('hidden')">
                                    <svg class="-ml-0.5 size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                                        data-slot="icon">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Delete Booking
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium mb-4">Shipping Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Vessel Name</p>
                                <p class="font-medium">
                                    @if (!empty($booking->vessel))
                                        {{ $booking->vessel }}
                                    @else
                                        <span class="italic text-red-500">Not set</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Voyage Number</p>
                                <p class="font-medium">
                                    @if (!empty($booking->voyage))
                                        {{ $booking->voyage }}
                                        @if (session('warning'))
                                            <p class="text-xs font-medium text-amber-800">{{ session('warning') }}</p>
                                        @endif
                                    @else
                                        <span class="italic text-red-500">Not set</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tug</p>
                                <p class="font-medium">
                                    @if (!empty($booking->tug))
                                        {{ $booking->tug }}
                                    @else
                                        <span class="italic text-red-500">Not set</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Delivery Terms</p>
                                <p class="font-medium">
                                    @if (!empty($booking->delivery_terms))
                                        {{ $booking->delivery_terms }}
                                    @else
                                        <span class="italic text-red-500">Not set</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Route Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium mb-4">Route Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Place of Receipt</p>
                                <p class="font-medium">{{ $booking->place_of_receipt }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Port of Loading</p>
                                <p class="font-medium">{{ $booking->pol }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Port of Discharge</p>
                                <p class="font-medium">{{ $booking->pod }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Place of Delivery</p>
                                <p class="font-medium">{{ $booking->place_of_delivery }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium mb-4">Schedule</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Estimated Time of Sailing</p>
                                <p class="font-medium">
                                    @if (!empty($booking->ets))
                                        {{ $booking->ets->format('Y-m-d H:i') }}
                                    @else
                                        <span class="italic text-red-500">Not set</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Estimated Time of Arrival</p>
                                <p class="font-medium">
                                    @if (!empty($booking->eta))
                                        {{ $booking->eta->format('Y-m-d H:i') }}
                                    @else
                                        <span class="italic text-red-500">Not set</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Instructions -->
                    <div class="bg-gray-50 p-4 rounded-lg space-y-4">
                        <div class="sm:flex sm:items-center">
                            <div class="sm:flex-auto">
                                <h3 class="text-lg font-medium">Shipping Instructions</h3>
                                <p class="text-sm text-red-600">
                                    @php
                                        // Group unallocated containers by cargo container_type
                                        $unallocatedByType = [];
                                        $totalUnallocated = 0;

                                        foreach ($booking->cargos as $cargo) {
                                            $unallocatedCount = $cargo->containers->filter(function ($container) {
                                                return is_null($container->shipping_instruction_id);
                                            })->count();

                                            if ($unallocatedCount > 0) {
                                                $type = $cargo->container_type ?? 'Unknown Type';
                                                if (!isset($unallocatedByType[$type])) {
                                                    $unallocatedByType[$type] = 0;
                                                }
                                                $unallocatedByType[$type] += $unallocatedCount;
                                                $totalUnallocated += $unallocatedCount;
                                            }
                                        }
                                    @endphp

                                    @if (count($unallocatedByType) > 0)
                                        <strong>Total Unallocated Containers (by Type):</strong><br>
                                        <ul class="list-disc ml-4">
                                            @foreach ($unallocatedByType as $type => $count)
                                                <li class="text-sm text-red-600"><strong>{{ $type }}: {{ $count }}</strong></li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <strong>All Containers Allocated</strong>
                                    @endif
                                </p>
                            </div>
                            @if($totalUnallocated > 0 && $booking->status > 1 && $booking->status < 4)
                            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                                <a href="{{ route('shipping-instructions.create', $booking) }}"
                                    class="inline-flex items-center gap-x-1.5 rounded-md bg-blue-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 uppercase tracking-widest">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                    </svg>
                                    Add Shipping Instruction
                                </a>
                            </div>
                            @endif
                        </div>

                        @if($booking->shippingInstructions->isEmpty())
                            <div class="rounded-md bg-yellow-50 p-4">
                                <div class="flex">
                                    <div class="shrink-0">
                                        <svg class="size-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"
                                            aria-hidden="true" data-slot="icon">
                                            <path fill-rule="evenodd"
                                                d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">Empty Shipping Instructions</h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <p>Please upload your shipping instructions to proceed.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($booking->shippingInstructions as $si)
                                    <div class="border rounded p-4 bg-white">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h1 class="text-lg font-medium">{{ $si->shipper }}</h1>
                                                <p class="text-sm text-gray-500">SI #: {{ $si->sub_booking_number ?? 'N/A' }}</p>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                Box Operator: {{ $si->box_operator ?? 'N/A' }}
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <p class="text-sm text-gray-600 mb-2">Allocated Containers</p>
                                            <div class="grid grid-cols-2 gap-4">
                                                @foreach($si->containers->groupBy('container_type') as $type => $containers)
                                                    <div class="text-sm">
                                                        <span class="font-medium">{{ $type }}:</span> 
                                                        {{ $containers->count() }} containers
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="mt-4 pt-4 border-t">
                                            <div class="grid grid-cols-2 gap-4 text-sm">
                                                <div>
                                                    <p class="text-gray-500">Consignee</p>
                                                    <p class="font-medium">{{ $si->consignee }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-500">Cargo Description</p>
                                                    <p class="font-medium">{{ $si->cargo_description }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right mt-4">

                                            <p class="text-left italic text-red-500 text-sm">
                                                Total SI Revisions after BL confirmed: {{ $si->post_bl_edit_count }}
                                                @php
                                                    $freeRevisionsLimit = 3;
                                                    $remainingFreeRevisions = max(0, $freeRevisionsLimit - $si->post_bl_edit_count);
                                                @endphp
                                                <br>
                                                <span class="{{ $remainingFreeRevisions > 0 ? 'text-green-500' : 'text-red-500' }}">
                                                    Remaining free revisions: {{ $remainingFreeRevisions }} of {{ $freeRevisionsLimit }}
                                                </span>
                                            </p>

                                            @if($booking->status < 5 && $remainingFreeRevisions > 0)
                                            <a href="{{ route('shipping-instructions.show', $si) }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                Edit
                                            </a>
                                            @elseif($booking->status < 5 && $remainingFreeRevisions <= 0)
                                            <a href="#"
                                                onclick="showRevisionWarning(event, '{{ $si->id }}')"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                Edit
                                            </a>
                                            @endif
                                            @if($booking->status == 3)
                                            <a href="{{ route('shipping-instructions.generate-bl', $si) }}"
                                                class="ml-4 text-green-600 hover:text-green-900">
                                                View BL
                                            </a>
                                            @endif
                                            @if($booking->status >= 4)
                                                @if($si->telex_bl_released)
                                                    <a href="{{ route('shipping-instructions.generate-telex-bl', $si) }}"
                                                        class="ml-4 text-green-600 hover:text-green-900">
                                                        Download Telex BL
                                                    </a>
                                                @else
                                                    <a href="{{ route('shipping-instructions.generate-bl', $si) }}"
                                                        class="ml-4 text-red-600 hover:text-red-900">
                                                        Download BL
                                                    </a>
                                                @endif
                                                @if(auth()->user()->role != 'customer')
                                                    <a href="{{ route('shipping-instructions.generate-manifest', $si) }}"
                                                        class="ml-4 text-green-600 hover:text-green-900">
                                                        Download Manifest
                                                    </a>
                                                @endif
                                            @endif
                                            @if($booking->status < 4)
                                            <form action="{{ route('shipping-instructions.destroy', $si) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="ml-4 text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Are you sure you want to delete this shipping instruction?')">
                                                    Delete
                                                </button>
                                            </form>
                                            @endif
                                            @if(auth()->user()->role != 'customer' && $booking->status == 4 && !$si->telex_bl_released)
                                                    <x-primary-button type="button" 
                                                        onclick="showTelexBlReleaseModal('{{ $si->id }}')"
                                                        class="ml-4">
                                                        Release Telex BL
                                                    </x-primary-button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>


                    <!-- Invoice Information -->
                    @if($booking->status >= 4)
                        <div x-data="{ invoiceType: '' }" class="bg-gray-50 p-4 rounded-lg">
                            <div class="sm:flex sm:items-center mb-4">
                                <div class="sm:flex-auto">
                                    <div class="flex items-center gap-3">
                                        <h3 class="text-lg font-medium">Invoice Information</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Invoice Form -->
                            @if(auth()->user()->role != 'customer')
                                <form action="{{ route('invoice.submit', $booking) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <!-- Invoice Actions -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                        <div class="col-span-1">
                                            <x-input-label for="invoice_name_select" :value="__('Invoice Type')" />
                                            <select id="invoice_name_select" 
                                                name="invoice_name_select" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" x-model="invoiceType">
                                                <option value="">Select Invoice Type</option>
                                                <option value="Revenue" {{ old('invoice_name') == 'Revenue' ? 'selected' : '' }}>Revenue</option>
                                                <option value="Recovery Charge" {{ old('invoice_name') == 'Recovery Charge' ? 'selected' : '' }}>Recovery Charge</option>
                                                <option value="Other" {{ old('invoice_name') == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('invoice_name')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-span-2">
                                            <x-input-label for="invoice_file" :value="__('Invoice File')" />
                                            <div class="flex items-center gap-2">
                                                <input type="file" 
                                                    id="invoice_file" 
                                                    name="invoice_file" 
                                                    class="mt-1 block w-full text-sm text-gray-500
                                                        file:file:py-2 file:px-2
                                                        file:rounded-md file:border-0
                                                        file:text-sm file:font-semibold
                                                        file:bg-indigo-50 file:text-indigo-700
                                                        hover:file:bg-indigo-100" />
                                                <button type="button"
                                                    x-show="invoiceType === 'Revenue' || invoiceType === 'Recovery Charge'"
                                                    onclick="extractInvoiceData()"
                                                    class="mt-1 inline-flex items-center justify-end gap-x-1.5 rounded-md bg-blue-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 uppercase tracking-widest">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                    </svg>
                                                    Extract
                                                </button>
                                            </div>
                                            @error('invoice_file')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Invoice Details -->
                                    <div x-show="invoiceType !== ''" class="grid grid-cols-5 gap-6">
                                        <div x-show="invoiceType === 'Other'">
                                            <x-input-label for="invoice_name" :value="__('Invoice Name')" />
                                            <x-text-input id="invoice_name" class="block mt-1 w-full" type="text" name="invoice_name" :value="old('invoice_name')" />
                                            @error('invoice_name')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <x-input-label for="invoice_date" :value="__('Date of Invoice')" />
                                            <x-text-input id="invoice_date" class="block mt-1 w-full" type="date" name="invoice_date" :value="old('invoice_date')" />
                                            @error('invoice_date')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <x-input-label for="invoice_number" :value="__('Invoice Number')" />
                                            <x-text-input id="invoice_number" class="block mt-1 w-full" type="text" name="invoice_number" :value="old('invoice_number')" />
                                            @error('invoice_number')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <x-input-label for="invoice_amount" :value="__('Amount (MYR)')" />
                                            <div class="relative mt-1">
                                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                    <span class="text-gray-500 sm:text-sm">RM</span>
                                                </div>
                                                <x-text-input 
                                                    id="invoice_amount" 
                                                    class="block w-full pl-12" 
                                                    type="number" 
                                                    step="0.01" 
                                                    name="invoice_amount" 
                                                    :value="old('invoice_amount')" 
                                                    placeholder="0.00"
                                                />
                                                @error('invoice_amount')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div>
                                            <x-input-label for="invoice_amount_usd" :value="__('Amount (USD)')" />
                                            <div class="relative mt-1">
                                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                    <span class="text-gray-500 sm:text-sm">USD</span>
                                                </div>
                                                <x-text-input 
                                                    id="invoice_amount_usd" 
                                                    class="block w-full pl-12" 
                                                    type="number" 
                                                    step="0.01" 
                                                    name="invoice_amount_usd" 
                                                    :value="old('invoice_amount_usd')" 
                                                    placeholder="0.00"
                                                />
                                                @error('invoice_amount_usd')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-end mt-4">
                                        <x-primary-button>
                                            Upload Invoice
                                        </x-primary-button>
                                    </div>
                                </form>
                            @endif

                            <!-- List of Uploaded Invoices -->
                            @if($booking->invoices && $booking->invoices->count() > 0)
                            <div class="mt-8">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Uploaded Invoices</h4>
                                <div class="overflow-x-auto pb-12">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice Name</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice Number</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount (MYR)</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($booking->invoices as $invoice)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $invoice->invoice_name ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $invoice->invoice_number }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RM {{ number_format($invoice->invoice_amount, 2) }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $invoice->status === 'Paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ $invoice->status }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                    <div class="relative inline-block text-left" x-data="{ open: false }">
                                                        <button @click="open = !open" type="button" class="text-indigo-600 hover:text-indigo-900 inline-flex items-center">
                                                            Download
                                                        </button>
                                                        <div x-show="open" 
                                                            @click.away="open = false"
                                                            x-transition:enter="transition ease-out duration-100"
                                                            x-transition:enter-start="transform opacity-0 scale-95"
                                                            x-transition:enter-end="transform opacity-100 scale-100"
                                                            x-transition:leave="transition ease-in duration-75"
                                                            x-transition:leave-start="transform opacity-100 scale-100"
                                                            x-transition:leave-end="transform opacity-0 scale-95"
                                                            class="origin-top-right absolute right-0 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                                            <div class="py-1">
                                                                <a href="{{ route('invoice.download', $invoice) }}" 
                                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                    Invoice
                                                                </a>
                                                                @if($invoice->payment)
                                                                <a href="{{ route('invoice.payment.download', $invoice->payment) }}" 
                                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                    Payment Receipt
                                                                </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if(!$invoice->payment && auth()->user()->role != 'customer')
                                                        <a onclick="showPaymentModal({{ $invoice->id }})" class="text-green-600 hover:text-green-900 cursor-pointer">Payment</a>
                                                    @endif
                                                    @if(auth()->user()->role != 'customer')
                                                        <form action="{{ route('invoice.delete', $invoice) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this invoice?')">Delete</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @else
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">No invoices uploaded yet.</h3>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Documents -->
                    @if($booking->status >= 4)
                    <div x-data="{ documentType: '' }" class="bg-gray-50 p-4 rounded-lg">
                        <div class="sm:flex sm:items-center mb-4">
                            <div class="sm:flex-auto">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-medium">Documents</h3>
                                </div>
                            </div>
                        </div>

                        <!-- Document Upload Form -->
                        @if(auth()->user()->role != 'customer')
                        <form action="{{ route('related-document.upload', $booking) }}" method="POST" enctype="multipart/form-data" class="space-y-4 mb-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="document_name_select" :value="__('Document Type')" />
                                    <select id="document_name_select" 
                                        name="document_name_select" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" x-model="documentType">
                                        <option value="">Select Document Type</option>
                                        <option value="Manifest" {{ old('document_type') == 'manifest' ? 'selected' : '' }}>Manifest</option>
                                        <option value="Container Load List" {{ old('document_type') == 'container_load_list' ? 'selected' : '' }}>Container Load List</option>
                                        <option value="Towing Certificate" {{ old('document_type') == 'towing_certificate' ? 'selected' : '' }}>Towing Certificate</option>
                                        <option value="Notice of Arrival" {{ old('document_type') == 'notice_of_arrival' ? 'selected' : '' }}>Notice of Arrival</option>
                                        <option value="Vendor Invoice CVS" {{ old('document_type') == 'vendor_invoice_cvs' ? 'selected' : '' }}>Vendor Invoice CVS</option>
                                        <option value="Vendor Invoice MRN" {{ old('document_type') == 'vendor_invoice_mrn' ? 'selected' : '' }}>Vendor Invoice MRN</option>
                                        <option value="Vendor Invoice Northsea" {{ old('document_type') == 'vendor_invoice_northsea' ? 'selected' : '' }}>Vendor Invoice Northsea</option>
                                        <option value="Credit Note" {{ old('document_type') == 'credit_note' ? 'selected' : '' }}>Credit Note</option>
                                        <option value="Store Charges" {{ old('document_type') == 'notice_of_arrival' ? 'selected' : '' }}>Store Charges</option>
                                        <option value="Other" {{ old('document_type') == 'notice_of_arrival' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('document_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <x-input-label for="document_file" :value="__('Document File')" />
                                    <input type="file" 
                                        id="document_file" 
                                        name="document_file" 
                                        class="mt-1 block w-full text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-md file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-indigo-50 file:text-indigo-700
                                            hover:file:bg-indigo-100" />
                                    @error('document_file')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Document Information -->
                            <div class="grid grid-cols-3 gap-6">
                                <div x-show="documentType === 'Other'">
                                    <x-input-label for="document_name" :value="__('Document Name')" />
                                    <x-text-input id="document_name" class="block mt-1 w-full" type="text" name="document_name" :value="old('document_name')" />
                                    @error('document_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div x-show="documentType === 'Other' ||
                                             documentType === 'Vendor Invoice' ||
                                             documentType === 'Vendor Invoice CVS' ||
                                             documentType === 'Vendor Invoice MRN' ||
                                             documentType === 'Vendor Invoice Northsea' ||
                                             documentType === 'Credit Note' ||
                                             documentType === 'Store Charges'">
                                    <x-input-label for="document_number" :value="__('Document Number')" />
                                    <x-text-input id="document_number" class="block mt-1 w-full" type="text" name="document_number" :value="old('document_number')" />
                                    @error('document_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div x-show="documentType === 'Other' ||
                                             documentType === 'Vendor Invoice' ||
                                             documentType === 'Vendor Invoice CVS' ||
                                             documentType === 'Vendor Invoice MRN' ||
                                             documentType === 'Vendor Invoice Northsea' ||
                                             documentType === 'Credit Note' ||
                                             documentType === 'Store Charges'">
                                    <x-input-label for="invoice_amount" :value="__('Amount (MYR)')" />
                                    <div class="relative mt-1">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 sm:text-sm">RM</span>
                                        </div>
                                        <x-text-input 
                                            id="invoice_amount" 
                                            class="block w-full pl-12" 
                                            type="number" 
                                            step="0.01" 
                                            name="invoice_amount" 
                                            :value="old('invoice_amount')" 
                                            placeholder="0.00"
                                        />
                                        @error('invoice_amount')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                    <div class="flex justify-end">
                                <x-primary-button>
                                    {{ __('Upload Document') }}
                                </x-primary-button>
                            </div>
                        </form>
                        @endif

                        <!-- List of Uploaded Documents -->
                        @if($booking->relatedDocuments && $booking->relatedDocuments->count() > 0)
                        <div class="mt-8">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Uploaded Documents</h4>
                                <div class="overflow-x-auto pb-12">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document Name</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($booking->relatedDocuments as $document)
                                                @php
                                                    // For customers, only show specific document types
                                                    $showDocument = true;
                                                    if (auth()->user()->role == 'customer') {
                                                        $allowedTypes = ['Manifest', 'Container Load List', 'Notice of Arrival', 'BL with Telex Release'];
                                                        $showDocument = in_array($document->document_name, $allowedTypes);
                                                    }
                                                @endphp
                                                
                                                @if($showDocument)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $document->document_name ?? 'N/A' }}</td>
                                                    
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                        <div class="relative inline-block text-left">
                                                            <a href="{{ route('related-document.download', [$booking, $document]) }}" class="text-indigo-600 hover:text-indigo-900 inline-flex items-center">
                                                                Download
                                                            </a>
                                                        </div>
                                                        @if(auth()->user()->role != 'customer')
                                                            <form action="{{ route('related-document.delete', [$booking, $document]) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this document?')">Delete</button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @else
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">No documents uploaded yet.</h3>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="m-6 flex justify-end space-x-4">
                        <div class="flex space-x-4">
                            <button onclick="window.history.back()"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Back
                            </button>

                            @if($booking->status == $status::NEW && $booking->sub_status == 0 && auth()->user()->role == 'customer')
                            <!-- Submit Booking Button -->
                            <div class="relative" x-data="{ showTooltip: false }">
                                <button type="button"
                                    @mouseover="showTooltip = true"
                                    @mouseleave="showTooltip = false"
                                    onclick="document.getElementById('booking-submission-modal').classList.remove('hidden')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest bg-blue-600 text-white hover:bg-blue-700">
                                    Submit Booking
                                </button>
                                <!-- Tooltip -->
                                <div x-show="showTooltip" 
                                    x-transition
                                    class="absolute bottom-full mb-2 w-64 p-2 bg-gray-800 text-white text-xs rounded shadow-lg"
                                    style="left: 10%; transform: translateX(-50%)">
                                    Please make sure all the information is correct before submitting the booking.
                                </div>
                            </div>
                            @elseif($booking->status == $status::NEW && $booking->sub_status == 1 && auth()->user()->role != 'customer')
                            <!-- Confirm Booking Button -->
                            <div class="relative" x-data="{ showTooltip: false }">
                                <button type="button"
                                    @mouseover="showTooltip = true"
                                    @mouseleave="showTooltip = false"
                                    onclick="document.getElementById('booking-confirmation-modal').classList.remove('hidden')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest 
                                        @if(!empty($booking->vessel) && !empty($booking->voyage))
                                            bg-blue-600 text-white hover:bg-blue-700
                                        @else
                                            bg-gray-300 text-gray-500 cursor-not-allowed
                                        @endif"
                                    @if(empty($booking->vessel) || empty($booking->voyage) || empty($booking->tug)) disabled @endif>
                                    Confirm Booking
                                </button>
                                <!-- Tooltip -->
                                @if(empty($booking->vessel) || empty($booking->voyage) || empty($booking->tug))
                                    <div x-show="showTooltip" 
                                        x-transition
                                        class="absolute bottom-full mb-2 w-64 p-2 bg-gray-800 text-white text-xs rounded shadow-lg"
                                        style="left: 10%; transform: translateX(-50%)">
                                        @if(empty($booking->vessel))
                                            Please add vessel before confirming the booking.
                                        @elseif(empty($booking->voyage))
                                            Please add voyage before confirming the booking.
                                        @elseif(empty($booking->tug))
                                            Please add tug before confirming the booking.
                                        @endif
                                    </div>
                                @endif
                            </div>
                            @elseif($booking->status == $status::BOOKING_CONFIRMED && auth()->user()->role == 'customer')
                            <!-- Submit SI Button -->
                            <div class="relative" x-data="{ showTooltip: false }">
                                <!-- Button with mouseover tooltip -->
                                <button type="button"
                                    @mouseover="showTooltip = true"
                                    @mouseleave="showTooltip = false"
                                    onclick="document.getElementById('si-submission-modal').classList.remove('hidden')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest 
                                        @if($booking->shippingInstructions->isNotEmpty() && $totalUnallocated === 0)
                                            bg-blue-600 text-white hover:bg-blue-700
                                        @else
                                            bg-gray-300 text-gray-500 cursor-not-allowed
                                        @endif"
                                    @if($booking->shippingInstructions->isEmpty() || $totalUnallocated > 0) disabled @endif>
                                    Submit SI 
                                </button>

                                <!-- Tooltip -->
                                <div x-show="showTooltip" 
                                    x-transition
                                    class="absolute bottom-full mb-2 w-64 p-2 bg-gray-800 text-white text-xs rounded shadow-lg"
                                    style="left: 10%; transform: translateX(-50%)">
                                    @if($booking->shippingInstructions->isEmpty())
                                        Please add at least one shipping instruction.
                                    @elseif($totalUnallocated > 0)
                                        Please allocate all containers to shipping instructions before submitting.
                                    @else
                                        Ready to submit shipping instructions.
                                    @endif
                                </div>
                            </div>

                            @elseif($booking->status == $status::BL_VERIFICATION && auth()->user()->role == 'customer')
                            <!-- Confirm BL Button -->
                            <div class="relative">
                                <button type="button"
                                    onclick="document.getElementById('bl-confirmation-modal').classList.remove('hidden')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest 
                                        bg-blue-600 text-white hover:bg-blue-700">
                                    Confirm BL
                                </button>
                            </div>
                            @elseif($booking->status == 'Pending Payment' && auth()->user()->role == 'customer')
                            <!-- Submit Payment Button -->
                            <div class="relative">
                                <button type="button"
                                    onclick="document.getElementById('payment-submission-modal').classList.remove('hidden')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest 
                                        bg-blue-600 text-white hover:bg-blue-700">
                                    Submit Payment
                                </button>
                            </div>
                            
                            @elseif($booking->status == $status::BL_CONFIRMED && auth()->user()->role != 'customer')
                            <!-- Sail Away Button -->
                                @if($booking->shippingInstructions->every(function($si) { return $si->telex_bl_released; }))
                                <div class="relative">
                                    <button type="button"
                                        onclick="document.getElementById('sailing-confirmation-modal').classList.remove('hidden')"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest 
                                            bg-blue-600 text-white hover:bg-blue-700">
                                        Sailing
                                    </button>
                                </div>
                                @else
                                    <div class="relative">
                                        <button type="button"
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest 
                                                bg-gray-300 text-gray-500 cursor-not-allowed">
                                        Sailing
                                    </button>
                                </div>
                                @endif
                            @elseif($booking->status == $status::SAILING && auth()->user()->role != 'customer')
                            <!-- Arrival Button -->
                                @if($booking->relatedDocuments->where('document_name', 'Container Load List')->first())
                                <div class="relative">
                                    <button type="button"
                                        onclick="document.getElementById('arrival-confirmation-modal').classList.remove('hidden')"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest 
                                            bg-blue-600 text-white hover:bg-blue-700">
                                        Arrived
                                        </button>
                                    </div>
                                @else
                                    <div class="relative">
                                        <button type="button"
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest 
                                                bg-gray-300 text-gray-500 cursor-not-allowed">
                                            Arrived
                                        </button>
                                    </div>
                                @endif
                            @elseif($booking->status == 6 && auth()->user()->role != 'customer')
                            <!-- Completed Button -->
                                @if($booking->invoices->first() &&
                                    $booking->invoices->every(function($invoice) { return $invoice->status == 'Paid'; }) &&
                                    $booking->relatedDocuments->where('document_name', 'Manifest')->first() &&
                                    $booking->relatedDocuments->where('document_name', 'Container Load List')->first() &&
                                    $booking->relatedDocuments->where('document_name', 'Towing Certificate')->first() &&
                                    $booking->relatedDocuments->where('document_name', 'Vendor Invoice CVS')->first() &&
                                    $booking->relatedDocuments->where('document_name', 'Vendor Invoice MRN')->first() &&
                                    $booking->relatedDocuments->where('document_name', 'Vendor Invoice Northsea')->first()
                                )
                                <div class="relative">
                                    <button type="button"
                                        onclick="document.getElementById('completed-confirmation-modal').classList.remove('hidden')"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest 
                                            bg-blue-600 text-white hover:bg-blue-700">
                                        Complete
                                    </button>
                                </div>
                                @else
                                    <div class="relative">
                                        <button type="button"
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest 
                                                bg-gray-300 text-gray-500 cursor-not-allowed">
                                            Complete
                                        </button>
                                    </div>
                                @endif
                            @endif

                            <!-- Completed Confirmation Modal -->
                            <div id="completed-confirmation-modal" class="hidden relative z-10" aria-labelledby="modal-title"
                                role="dialog" aria-modal="true">
                                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
                                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                    <div
                                        class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                        <div
                                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                            <div>
                                                <div class="mt-3 text-center sm:mt-5">
                                                    <h3 class="text-base font-semibold text-gray-900" id="modal-title">Completed Confirmation</h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">Once the booking is completed, you will not be able to make any changes to the booking.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-5 flex justify-between items-center sm:mt-6">
                                                <button type="button" onclick="document.getElementById('completed-confirmation-modal').classList.add('hidden')"
                                                    class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancel</button>
                                                <div class="flex gap-3">
                                                    <button type="button" onclick="window.location.href='{{ route('booking.completed', $booking) }}'"
                                                        class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Arrival Confirmation Modal -->
                            <div id="arrival-confirmation-modal" class="hidden relative z-10" aria-labelledby="modal-title"
                                role="dialog" aria-modal="true">
                                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
                                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                    <div
                                        class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                        <div
                                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                            <div>
                                                <div class="mt-3 text-center sm:mt-5">
                                                    <h3 class="text-base font-semibold text-gray-900" id="modal-title">Arrival Confirmation</h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">Please upload the Notice of Arrival before confirming the arrival.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-5 flex justify-between items-center sm:mt-6">
                                                <button type="button" onclick="document.getElementById('arrival-confirmation-modal').classList.add('hidden')"
                                                    class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancel</button>
                                                <div class="flex gap-3">
                                                    <button type="button" onclick="window.location.href='{{ route('booking.arrived', $booking) }}'"
                                                        class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Telex BL Release Modal -->
                            <div id="telex-bl-release-modal" class="hidden relative z-10" aria-labelledby="modal-title"
                                role="dialog" aria-modal="true">
                                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
                                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                    <div
                                        class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                        <div
                                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                            <div>
                                                <div class="mt-3 text-center sm:mt-5">
                                                    <h3 class="text-base font-semibold text-gray-900" id="modal-title">Telex BL Release</h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">Are you sure to release the Telex BL for this box operator?</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-5 flex justify-between items-center sm:mt-6">
                                                <button type="button" onclick="document.getElementById('telex-bl-release-modal').classList.add('hidden')"
                                                    class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancel</button>
                                                <div class="flex gap-3">
                                                    <button type="button" id="confirm-telex-bl-release"
                                                        class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                function showTelexBlReleaseModal(siId) {
                                    const modal = document.getElementById('telex-bl-release-modal');
                                    const confirmButton = document.getElementById('confirm-telex-bl-release');
                                    
                                    // Update the confirm button's onclick handler with the current SI ID
                                    confirmButton.onclick = function() {
                                        window.location.href = `/shipping-instructions/${siId}/release-telex-bl`;
                                    };
                                    
                                    modal.classList.remove('hidden');
                                }
                            </script>

                            <!-- Sailing Confirmation Modal -->
                            <div id="sailing-confirmation-modal" class="hidden relative z-10" aria-labelledby="modal-title"
                                role="dialog" aria-modal="true">
                                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
                                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                    <div
                                        class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                        <div
                                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                            <div>
                                                <div class="mt-3 text-center sm:mt-5">
                                                    <h3 class="text-base font-semibold text-gray-900" id="modal-title">Sailing Confirmation</h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">Please confirm that all the information are correct before sailing.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-5 flex justify-between items-center sm:mt-6">
                                                <button type="button" onclick="document.getElementById('sailing-confirmation-modal').classList.add('hidden')"
                                                    class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancel</button>
                                                <div class="flex gap-3">
                                                    <button type="button" onclick="window.location.href='{{ route('booking.sailing', $booking) }}'"
                                                        class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Booking Deletion Modal -->
                            <div id="booking-deletion-modal" class="hidden relative z-10" aria-labelledby="modal-title"
                                role="dialog" aria-modal="true">
                                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
                                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                    <div
                                        class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                        <div
                                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                            <div>
                                                <div class="mt-3 text-center sm:mt-5">
                                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                                                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                                        </svg>
                                                    </div>
                                                    <h3 class="text-base font-semibold text-gray-900 mt-4" id="modal-title">Booking Deletion</h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">Are you sure to delete this booking? This action is irreversible.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-5 flex justify-between items-center sm:mt-6">
                                                <button type="button" onclick="document.getElementById('booking-deletion-modal').classList.add('hidden')"
                                                    class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancel</button>
                                                <form action="{{ route('booking.delete', $booking) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="flex gap-3">
                                                        <button type="submit"
                                                            class="inline-flex justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Confirm</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Booking Cancellation Modal -->
                            <div id="booking-cancellation-modal" class="hidden relative z-10" aria-labelledby="modal-title"
                                role="dialog" aria-modal="true">
                                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
                                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                    <div
                                        class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                        <div
                                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                            <div>
                                                <div class="mt-3 text-center sm:mt-5">
                                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                                                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                                        </svg>
                                                    </div>
                                                    <h3 class="text-base font-semibold text-gray-900 mt-4" id="modal-title">Booking Cancellation</h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">Are you sure to cancel this booking? This action is irreversible.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-5 flex justify-between items-center sm:mt-6">
                                                <button type="button" onclick="document.getElementById('booking-cancellation-modal').classList.add('hidden')"
                                                    class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancel</button>
                                                <div class="flex gap-3">
                                                    <button type="button" onclick="window.location.href='{{ route('booking.cancel', $booking) }}'"
                                                        class="inline-flex justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Booking Submission Modal -->
                            <div id="booking-submission-modal" class="hidden relative z-10" aria-labelledby="modal-title"
                                role="dialog" aria-modal="true">
                                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
                                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                    <div
                                        class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                        <div
                                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                            <div>
                                                <div class="mt-3 text-center sm:mt-5">
                                                    <h3 class="text-base font-semibold text-gray-900" id="modal-title">Booking Submission</h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">Please confirm that all the information are correct before submitting the booking.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-5 flex justify-between items-center sm:mt-6">
                                                <button type="button" onclick="document.getElementById('booking-submission-modal').classList.add('hidden')"
                                                    class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancel</button>
                                                <div class="flex gap-3">
                                                    <button type="button" onclick="window.location.href='{{ route('booking.submit', $booking) }}'"
                                                        class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Booking Confirmation Modal -->
                            <div id="booking-confirmation-modal" class="hidden relative z-10" aria-labelledby="modal-title"
                                role="dialog" aria-modal="true">
                                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
                                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                    <div
                                        class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                        <div
                                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                            <div>
                                                <div class="mt-3 text-center sm:mt-5">
                                                    <h3 class="text-base font-semibold text-gray-900" id="modal-title">Booking Confirmation</h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">Please confirm that all the information are correct before confirming the booking.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-5 flex justify-between items-center sm:mt-6">
                                                <button type="button" onclick="document.getElementById('booking-confirmation-modal').classList.add('hidden')"
                                                    class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancel</button>
                                                <div class="flex gap-3">
                                                    <button type="button" onclick="window.location.href='{{ route('booking.confirm', $booking) }}'"
                                                        class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- BL Confirmation Modal -->
                            <div id="bl-confirmation-modal" class="hidden relative z-10" aria-labelledby="modal-title"
                                role="dialog" aria-modal="true">
                                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
                                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                    <div
                                        class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                        <div
                                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                            <div>
                                                <div class="mt-3 text-center sm:mt-5">
                                                    <h3 class="text-base font-semibold text-gray-900" id="modal-title">BL Confirmation</h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">Please confirm that all the information are correct before confirming the BL. You are allowed to make changes to the BL 3 times after the first confirmation. Extra charges will be applied for each extra change after the 3rd change.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-5 flex justify-between items-center sm:mt-6">
                                                <button type="button" onclick="document.getElementById('bl-confirmation-modal').classList.add('hidden')"
                                                    class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancel</button>
                                                <div class="flex gap-3">
                                                    <button type="button" onclick="window.location.href='{{ route('booking.confirm-bl', $booking) }}'"
                                                        class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Confirmation Modal -->
                            <div id="payment-confirmation-modal" class="hidden relative z-10" aria-labelledby="modal-title"
                                role="dialog" aria-modal="true">
                                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
                                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                    <div
                                        class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                        <div
                                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                            <div>
                                                <div class="mt-3 text-center sm:mt-5">
                                                    <h3 class="text-base font-semibold text-gray-900" id="modal-title">Payment Verification</h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">Please confirm that all the information are correct before verifying the payment. Confirming the payment will allow the customer to download BL and Manifest.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-5 flex justify-between items-center sm:mt-6">
                                                <button type="button" onclick="document.getElementById('payment-confirmation-modal').classList.add('hidden')"
                                                    class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancel</button>
                                                <div class="flex gap-3">
                                                    <button type="button" onclick="window.location.href='{{ route('booking.reject-payment', $booking) }}'"
                                                        class="inline-flex justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Reject</button>
                                                    <button type="button" onclick="window.location.href='{{ route('booking.confirm-payment', $booking) }}'"
                                                        class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                           

                            <!-- Payment Submission Modal -->
                            <div id="payment-submission-modal" class="hidden relative z-10" aria-labelledby="modal-title"
                                role="dialog" aria-modal="true">
                                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
                                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                    <div
                                        class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                        <div
                                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                            <div>
                                                <div class="mt-3 text-center sm:mt-5">
                                                    <h3 class="text-base font-semibold text-gray-900" id="modal-title">
                                                        Confirm Payment Submission</h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">Please confirm that all the information are correct before submitting the Payment.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                                                <button type="button" onclick="document.getElementById('payment-form').submit();"
                                                    class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:col-start-2">Submit Payment</button>
                                                <button type="button"
                                                    onclick="document.getElementById('payment-submission-modal').classList.add('hidden')"
                                                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:col-start-1 sm:mt-0">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SI Confirmation Modal -->
                            <div id="si-submission-modal" class="hidden relative z-10" aria-labelledby="modal-title"
                                role="dialog" aria-modal="true">
                                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
                                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                    <div
                                        class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                        <div
                                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                            <div>
                                                <div class="mt-3 text-center sm:mt-5">
                                                    <h3 class="text-base font-semibold text-gray-900" id="modal-title">
                                                        Confirm Shipping Instructions Submission</h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">Please confirm that all the information are correct before submitting the Shipping Instructions.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                                                <button type="button" onclick="window.location.href='{{ route('booking.submit-si', $booking) }}'"
                                                    class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:col-start-2">Submit SI</button>
                                                <button type="button"
                                                    onclick="document.getElementById('si-submission-modal').classList.add('hidden')"
                                                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:col-start-1 sm:mt-0">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Invoice Confirmation Modal -->
                            <div id="invoice-submission-modal" class="hidden relative z-10" aria-labelledby="modal-title"
                                role="dialog" aria-modal="true">
                                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
                                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                    <div
                                        class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                        <div
                                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                            <div>
                                                <div class="mt-3 text-center sm:mt-5">
                                                    <h3 class="text-base font-semibold text-gray-900" id="modal-title">
                                                        Confirm Invoice Submission</h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">Please confirm that all the information are correct before submitting the Invoice. You will not be able to make any changes after submitting.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                                                <button type="button" onclick="document.getElementById('invoice-form').submit();"
                                                    class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:col-start-2">Submit Invoice</button>
                                                <button type="button"
                                                    onclick="document.getElementById('invoice-submission-modal').classList.add('hidden')"
                                                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:col-start-1 sm:mt-0">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<!-- Payment Modal -->
<div id="payment-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Upload Payment Slip</h3>
                <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mt-2">
                <form id="payment-form" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Payment Actions -->
                    <div class="mb-6">
                        <div class="flex items-center gap-1">
                            <div class="flex items-center gap-2">
                                <div class="relative">
                                    <input type="file" 
                                        id="modal_payment_file" 
                                        name="payment_file" 
                                        accept=".jpeg,.png,.jpg,.pdf,.heic,.heif"
                                        class="hidden"
                                        required
                                        onchange="updateModalPaymentFileName(this)">
                                    <label for="modal_payment_file" 
                                        class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                        Upload Payment Slip
                                    </label>
                                    <span id="modal_payment_file_name" class="ml-2 text-sm text-gray-500"></span>
                                    @error('payment_file')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <x-input-label for="modal_payment_date" :value="__('Payment Date')" />
                            <x-text-input id="modal_payment_date" class="block mt-1 w-full" type="date" name="payment_date" :value="old('payment_date')" required />
                            @error('payment_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <x-input-label for="modal_payment_amount" :value="__('Payment Amount')" />
                            <x-text-input id="modal_payment_amount" class="block mt-1 w-full" type="number" step="0.01" name="payment_amount" :value="old('payment_amount')" required />
                            @error('payment_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <x-input-label for="modal_payment_method" :value="__('Payment Method')" />
                            <select id="modal_payment_method" 
                                name="payment_method" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <option value="">Select Payment Method</option>
                                <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="Credit Card" {{ old('payment_method') == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                                <option value="Debit Card" {{ old('payment_method') == 'Debit Card' ? 'selected' : '' }}>Debit Card</option>
                                <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button type="button" onclick="closePaymentModal()" class="mr-3 inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center gap-x-1.5 rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 uppercase tracking-widest">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Submit Receipt
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Revision Warning Modal -->
<div id="revisionWarningModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Additional Charges Warning</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    You have exceeded the free revision limit. Additional charges may apply for this revision.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="continueButton" onclick="continueToEdit()" class="px-4 py-2 bg-indigo-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Continue
                </button>
                <button onclick="closeModal()" class="ml-3 px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function updateFileName(input) {
    const fileName = input.files[0]?.name || '';
    document.getElementById('file_name').textContent = fileName;
}

function extractInvoiceData() {
    const fileInput = document.getElementById('invoice_file');
    if (!fileInput.files.length) {
        alert('Please select a PDF file first');
        return;
    }

    const formData = new FormData();
    formData.append('invoice_file', fileInput.files[0]);
    formData.append('_token', '{{ csrf_token() }}');

    // Show loading state
    const extractButton = event.currentTarget;
    const originalContent = extractButton.innerHTML;
    extractButton.innerHTML = `
        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Processing...
    `;
    extractButton.disabled = true;

    // First extract data
    fetch('{{ route("invoice.extract", $booking) }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Populate the form fields with extracted data
            document.getElementById('invoice_date').value = data.invoice_date || '';
            document.getElementById('invoice_number').value = data.invoice_number || '';
            document.getElementById('invoice_amount').value = data.invoice_amount || '';
            document.getElementById('invoice_amount_usd').value = data.invoice_amount_usd || '';
            
            // Then upload the file
            //return uploadInvoice(formData);
        } else {
            throw new Error(data.message || 'Failed to extract data from PDF');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'An error occurred while processing the PDF');
    })
    .finally(() => {
        // Restore button state
        extractButton.innerHTML = originalContent;
        extractButton.disabled = false;
    });
}

function updatePaymentFileName(input) {
    const fileName = input.files[0]?.name || '';
    document.getElementById('payment_file_name').textContent = fileName;
}

function updateModalPaymentFileName(input) {
    const fileName = input.files[0]?.name || '';
    document.getElementById('modal_payment_file_name').textContent = fileName;
}

function showRevisionWarning(event, siId) {
    event.preventDefault();
    const modal = document.getElementById('revisionWarningModal');
    modal.classList.remove('hidden');
    
    // Store the SI ID for the continue button
    document.getElementById('continueButton').setAttribute('data-si-id', siId);
}

function continueToEdit() {
    const siId = document.getElementById('continueButton').getAttribute('data-si-id');
    window.location.href = `/shipping-instructions/${siId}`;
}

function closeModal() {
    const modal = document.getElementById('revisionWarningModal');
    modal.classList.add('hidden');
}

function showPaymentModal(invoiceId) {
    // Set the form action with the invoice ID
    document.getElementById('payment-form').action = `/invoice/payment/submit/${invoiceId}`;
    document.getElementById('payment-modal').classList.remove('hidden');
}

function closePaymentModal() {
    document.getElementById('payment-modal').classList.add('hidden');
}

// Update the payment link to use the modal
document.addEventListener('DOMContentLoaded', function() {
    const paymentLinks = document.querySelectorAll('a[onclick="document.getElementById(\'payment-form\').submit();"]');
    paymentLinks.forEach(link => {
        link.onclick = function(e) {
            e.preventDefault();
            // Get the invoice ID from the data attribute or other source
            const invoiceId = this.getAttribute('data-invoice-id');
            showPaymentModal(invoiceId);
        };
    });
});
</script>

