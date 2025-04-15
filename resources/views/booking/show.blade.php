<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

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
                @if ($booking->status == $status::NEW)
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
                        message="This shipment has arrived at the destination port. The complete button will be enabled once the invoice is paid."
                        color="green"
                    />
                    @endif
                @endif

                <div class="mt-4">
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
                                <x-status-badge text="{{ $statusLabel }}" color="green"/>
                            </div>
                            <p class="text-gray-600">{{ $booking->booking_number }}</p>
                        </div>
                        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                            @if($booking->status != $status::COMPLETED)
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
                            <button type="button"
                                class="inline-flex items-center gap-x-1.5 rounded-md bg-red-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 uppercase tracking-widest">
                                <svg class="-ml-0.5 size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                                    data-slot="icon">
                                    <path fill-rule="evenodd"
                                        d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                        clip-rule="evenodd" />
                                </svg>
                                Cancel Booking
                            </button>
                            @endif
                            @if($booking->status == $status::CANCELLED)
                            <button type="button"
                                class="inline-flex items-center gap-x-1.5 rounded-md bg-red-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 uppercase tracking-widest">
                                <svg class="-ml-0.5 size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                                    data-slot="icon">
                                    <path fill-rule="evenodd"
                                        d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                        clip-rule="evenodd" />
                                </svg>
                                Delete Booking
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Vessel Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium mb-4">Vessel Information</h3>
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
                                     $totalUnallocated = $booking->cargos->sum(function($cargo) {
                                        return $cargo->containers->filter(function($container) {
                                            return $container->shipping_instruction_id == null;
                                        })->count();
                                    });
                                    @endphp
                                    @if($totalUnallocated > 0)
                                        <strong>Total Unallocated Containers:</strong>
                                        {{ $totalUnallocated }}
                                    @else
                                        <strong>All Containers Allocated</strong>
                                    @endif
                                </p>
                            </div>
                            @if($totalUnallocated > 0 && $booking->status == $status::BOOKING_CONFIRMED)
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
                                            @if($booking->status < 5)
                                            <a href="{{ route('shipping-instructions.show', $si) }}"
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
                                            @if($booking->status == 4 || $booking->status == 5)
                                            <a href="{{ route('shipping-instructions.generate-bl', $si) }}"
                                                class="ml-4 text-green-600 hover:text-green-900">
                                                Download BL
                                            </a>
                                            <a href="{{ route('shipping-instructions.generate-manifest', $si) }}"
                                                class="ml-4 text-green-600 hover:text-green-900">
                                                Download Manifest
                                            </a>
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
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Invoice Information -->
                    @if($booking->status >= 4 && is_null($booking->invoice) && auth()->user()->role != 'customer')
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="sm:flex sm:items-center mb-4">
                                <div class="sm:flex-auto">
                                    <div class="flex items-center gap-3">
                                        <h3 class="text-lg font-medium">Invoice Information</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Invoice Form -->
                            <form action="{{ route('booking.submit-invoice', $booking) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <!-- Invoice Actions -->
                                <div class="mb-6">
                                    <div class="flex items-center gap-1">
                                        <div class="flex items-center gap-2">
                                            <div class="relative">
                                                <input type="file" 
                                                    id="invoice_file" 
                                                    name="invoice_file" 
                                                    accept=".pdf"
                                                    class="hidden"
                                                    onchange="updateFileName(this)">
                                                <label for="invoice_file" 
                                                    class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 cursor-pointer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                    </svg>
                                                    Select PDF Invoice
                                                </label>
                                                <span id="file_name" class="ml-2 text-sm text-gray-500"></span>
                                                @error('invoice_file')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <button type="button"
                                                onclick="extractInvoiceData()"
                                                class="inline-flex items-center gap-x-1.5 rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 uppercase tracking-widest">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                </svg>
                                                Extract
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Invoice Details -->
                                <div class="grid grid-cols-4 gap-6">
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
                                        <x-input-label for="invoice_amount" :value="__('Invoice Amount')" />
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
                                    <div class="flex items-center gap-2 pt-6">
                                        <button type="submit" class="inline-flex items-center gap-x-1.5 rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 uppercase tracking-widest">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                            Upload Invoice
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    <!-- Invoice Details after submission -->
                    @elseif($booking->invoice && $booking->status >= 4)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <h3 class="text-lg font-medium">Invoice Details</h3>
                                @if($booking->invoice->status === 'Unpaid')
                                    <x-status-badge text="{{ $booking->invoice->status }}" color="red"/>
                                @else
                                    <x-status-badge text="{{ $booking->invoice->status }}" color="green"/>
                                @endif
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Invoice Date</p>
                                    <p class="font-medium">{{ $booking->invoice->invoice_date->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Invoice Number</p>
                                    <p class="font-medium">{{ $booking->invoice->invoice_number }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Invoice Amount</p>
                                    <p class="font-medium">RM {{ number_format($booking->invoice->invoice_amount, 2) }}</p>
                                </div>
                            </div>
                            <div class="text-right mt-4">
                                <a href="{{ route('invoice.download', $booking) }}"
                                    class="text-indigo-600 hover:text-indigo-900">
                                    Download Invoice
                                </a>
                            </div>
                        </div>
                    @elseif($booking->status >= 4)
                        <div class="rounded-md bg-yellow-50 p-4">
                            <div class="flex">
                                <div class="shrink-0">
                                    <svg class="size-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Invoice Not Found</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>The invoice details are not available.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Payment Information -->
                    @if($booking->status >= 4 && $booking->invoice && is_null($booking->invoice->payment) && auth()->user()->role == 'customer')
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="sm:flex sm:items-center mb-4">
                                <div class="sm:flex-auto">
                                    <div class="flex items-center gap-3">
                                        <h3 class="text-lg font-medium">Payment Information</h3>
                                    </div>
                                </div>
                            </div>
                            <!-- Payment Form -->
                            <form action="{{ route('payment.submit', $booking) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <!-- Payment Actions -->
                                <div class="mb-6">
                                    <div class="flex items-center gap-1">
                                        <div class="flex items-center gap-2">
                                            <div class="relative">
                                                <input type="file" 
                                                    id="payment_file" 
                                                    name="payment_file" 
                                                    accept=".jpeg,.png,.jpg,.pdf,.heic,.heif"
                                                    class="hidden"
                                                    onchange="updatePaymentFileName(this)">
                                                <label for="payment_file" 
                                                    class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 cursor-pointer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                    </svg>
                                                    Upload Payment Slip
                                                </label>
                                                <span id="payment_file_name" class="ml-2 text-sm text-gray-500"></span>
                                                @error('payment_file')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Details -->
                                <div class="grid grid-cols-4 gap-6">
                                    <div>
                                        <x-input-label for="payment_date" :value="__('Payment Date')" />
                                        <x-text-input id="payment_date" class="block mt-1 w-full" type="date" name="payment_date" :value="old('payment_date')" />
                                        @error('payment_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <x-input-label for="payment_amount" :value="__('Payment Amount')" />
                                        <x-text-input id="payment_amount" class="block mt-1 w-full" type="number" step="0.01" name="payment_amount" :value="old('payment_amount')" />
                                        @error('payment_amount')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <x-input-label for="payment_method" :value="__('Payment Method')" />
                                        <select id="payment_method" 
                                            name="payment_method" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
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
                                    <div class="flex items-center gap-2 pt-6">
                                        <button type="submit" class="inline-flex items-center gap-x-1.5 rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 uppercase tracking-widest">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                            Submit Receipt
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    <!-- Payment Details after submission -->
                    @elseif($booking->status >= 4 && $booking->invoice && $booking->invoice->payment)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center gap-3 mb-4">
                                <h3 class="text-lg font-medium">Payment Details</h3>
                                @if($booking->invoice->payment->status === 'Confirmed')
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/10">{{ $booking->invoice->payment->status }}</span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">{{ $booking->invoice->payment->status }}</span>
                                @endif
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Payment Date</p>
                                    <p class="font-medium">{{ $booking->invoice->payment->payment_date->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Payment Amount</p>
                                    <p class="font-medium">RM {{ number_format($booking->invoice->payment->payment_amount, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Payment Method</p>
                                    <p class="font-medium">{{ ucfirst($booking->invoice->payment->payment_method) }}</p>
                                </div>
                                <div>
                                    @if($booking->invoice->payment->status === 'Pending Verification' && auth()->user()->role != 'customer')
                                        <!-- Confirm Payment Button -->
                                        <div class="relative">
                                            <button type="button"
                                                onclick="document.getElementById('payment-confirmation-modal').classList.remove('hidden')"
                                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest 
                                                    bg-blue-600 text-white hover:bg-blue-700">
                                                Verify Payment
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right mt-4">
                                <a href="{{ route('payment.download', $booking->invoice) }}"
                                    class="text-indigo-600 hover:text-indigo-900">
                                    Download Payment Slip
                                </a>
                            </div>
                        </div>
                    @elseif($booking->status >= 4 && $booking->invoice)
                        <div class="rounded-md bg-yellow-50 p-4">
                            <div class="flex">
                                <div class="shrink-0">
                                    <svg class="size-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Payment Slip Not Found</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>The payment slip details are not available.</p>
                                    </div>
                                </div>
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

                            @if($booking->status == $status::NEW && auth()->user()->role != 'customer')
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
                                    @if(empty($booking->vessel) || empty($booking->voyage)) disabled @endif>
                                    Confirm Booking
                                </button>
                                <!-- Tooltip -->
                                @if(empty($booking->vessel) || empty($booking->voyage))
                                    <div x-show="showTooltip" 
                                        x-transition
                                        class="absolute bottom-full mb-2 w-64 p-2 bg-gray-800 text-white text-xs rounded shadow-lg"
                                        style="left: 10%; transform: translateX(-50%)">
                                        @if(empty($booking->vessel))
                                            Please add vessel before confirming the booking.
                                        @elseif(empty($booking->voyage))
                                            Please add voyage before confirming the booking.
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
                            <div class="relative">
                                <button type="button"
                                    onclick="document.getElementById('sailing-confirmation-modal').classList.remove('hidden')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest 
                                        bg-blue-600 text-white hover:bg-blue-700">
                                    Sailing
                                </button>
                            </div>
                            @elseif($booking->status == $status::SAILING && auth()->user()->role != 'customer')
                            <!-- Arrival Button -->
                            <div class="relative">
                                <button type="button"
                                    onclick="document.getElementById('arrival-confirmation-modal').classList.remove('hidden')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest 
                                        bg-blue-600 text-white hover:bg-blue-700">
                                    Arrived
                                </button>
                            </div>
                            @elseif($booking->status == $status::ARRIVED && $booking->invoice->status == 'Paid' && auth()->user()->role != 'customer')
                            <!-- Completed Button -->
                            <div class="relative">
                                <button type="button"
                                    onclick="document.getElementById('completed-confirmation-modal').classList.remove('hidden')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest 
                                        bg-blue-600 text-white hover:bg-blue-700">
                                    Completed
                                </button>
                            </div>
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
                                                        <p class="text-sm text-gray-500">Please confirm that all the information are correct before sailing.</p>
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
</script>
