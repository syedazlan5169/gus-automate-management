<x-app-layout>
    @php
        // Check for the most recent rejected SI change request for this booking (customer side only)
        // Only show modal if there's a rejected request AND no active request exists
        // AND the most recent request is not approved_applied
        $rejectedRequest = null;
        if (auth()->check() && auth()->user()->role === 'customer') {
            // Check if there are any active requests (if there is, don't show the modal)
            $hasActiveRequest = \App\Models\SiChangeRequest::where('booking_id', $booking->id)
                ->whereNotIn('status', [
                    \App\Models\SiChangeRequest::STATUS_APPROVED_APPLIED,
                    \App\Models\SiChangeRequest::STATUS_REJECTED,
                    \App\Models\SiChangeRequest::STATUS_CANCELLED,
                    \App\Models\SiChangeRequest::STATUS_EXPIRED,
                ])
                ->exists();
            
            // Check the most recent request - if it's approved_applied, don't show rejection modal
            $mostRecentRequest = \App\Models\SiChangeRequest::where('booking_id', $booking->id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            // Only show rejected request modal if:
            // 1. No active request exists
            // 2. Most recent request is not approved_applied (or doesn't exist)
            if (!$hasActiveRequest && (!$mostRecentRequest || $mostRecentRequest->status !== \App\Models\SiChangeRequest::STATUS_APPROVED_APPLIED)) {
                $rejectedRequest = \App\Models\SiChangeRequest::where('booking_id', $booking->id)
                    ->where('status', \App\Models\SiChangeRequest::STATUS_REJECTED)
                    ->with(['shippingInstruction', 'approver', 'finalReviewer'])
                    ->orderBy('final_decision_at', 'desc')
                    ->orderBy('updated_at', 'desc')
                    ->first();
            }
        }
    @endphp
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
                                    // Check if user is customer
                                    $isCustomer = auth()->user()->role == 'customer';
                                    
                                    // For customers, skip Sailing & Arrived statuses
                                    if ($isCustomer) {
                                        $totalSteps = 5; // Total steps for customers (excluding SAILING and ARRIVED)
                                        
                                        // Check if required documents are uploaded for direct completion
                                        $hasInvoice = $booking->invoices()->exists();
                                        $hasCLL = $booking->relatedDocuments()->where('document_name', 'Container Load List')->exists();
                                        $hasK4OrK5 = $booking->relatedDocuments()->whereIn('document_name', ['K4', 'K5'])->exists();
                                        
                                        // If all required documents are uploaded, mark as completed
                                        if ($hasInvoice && $hasCLL && $hasK4OrK5 && $booking->status >= $status::BL_CONFIRMED) {
                                            $currentStep = 5; // Completed
                                        } else {
                                            // Map status to step number for customers
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
                                                case $status::ARRIVED:
                                                    $currentStep = 4; // Stay at BL_CONFIRMED step
                                                    break;
                                                case $status::COMPLETED:
                                                    $currentStep = 5;
                                                    break;
                                                case $status::CANCELLED:
                                                    $currentStep = 0;
                                                    break;
                                                default:
                                                    $currentStep = 0;
                                            }
                                        }
                                    } else {
                                        // For non-customers, keep original flow
                                        $totalSteps = 7;
                                        
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
                                                $currentStep = 0;
                                                break;
                                            default:
                                                $currentStep = 0;
                                        }
                                    }
                                    
                                    // Calculate percentage (0% for cancelled, 100% for completed)
                                    $progressPercentage = $booking->status == $status::CANCELLED ? 0 : ($currentStep / $totalSteps) * 100;
                                    
                                    // Determine color based on status
                                    $progressColor = $booking->status == $status::CANCELLED ? 'bg-red-600' : 'bg-indigo-600';
                                @endphp
                                <div class="h-2 rounded-full {{ $progressColor }}" style="width: {{ $progressPercentage }}%"></div>
                            </div>
                            @if($isCustomer)
                                <div class="mt-6 hidden grid-cols-5 gap-4 text-sm font-medium text-gray-600 sm:grid">
                                    <div class="text-center {{ $booking->status >= $status::NEW ? 'text-indigo-600' : 'text-gray-400' }}">Booking<br>Created</div>
                                    <div class="text-center {{ $booking->status >= $status::BOOKING_CONFIRMED ? 'text-indigo-600' : 'text-gray-400' }}">Booking<br>Confirmed</div>
                                    <div class="text-center {{ $booking->status >= $status::BL_VERIFICATION ? 'text-indigo-600' : 'text-gray-400' }}">BL<br>Verification</div>
                                    <div class="text-center {{ $booking->status >= $status::BL_CONFIRMED ? 'text-indigo-600' : 'text-gray-400' }}">BL<br>Confirmed</div>
                                    <div class="text-center {{ ($booking->status >= $status::COMPLETED || ($hasInvoice && $hasCLL && $hasK4OrK5 && $booking->status >= $status::BL_CONFIRMED)) ? 'text-indigo-600' : 'text-gray-400' }}">Completed</div>
                                </div>
                            @else
                                <div class="mt-6 hidden grid-cols-7 gap-4 text-sm font-medium text-gray-600 sm:grid">
                                    <div class="text-center {{ $booking->status >= $status::NEW ? 'text-indigo-600' : 'text-gray-400' }}">Booking<br>Created</div>
                                    <div class="text-center {{ $booking->status >= $status::BOOKING_CONFIRMED ? 'text-indigo-600' : 'text-gray-400' }}">Booking<br>Confirmed</div>
                                    <div class="text-center {{ $booking->status >= $status::BL_VERIFICATION ? 'text-indigo-600' : 'text-gray-400' }}">BL<br>Verification</div>
                                    <div class="text-center {{ $booking->status >= $status::BL_CONFIRMED ? 'text-indigo-600' : 'text-gray-400' }}">BL<br>Confirmed</div>
                                    <div class="text-center {{ $booking->status >= $status::SAILING ? 'text-indigo-600' : 'text-gray-400' }}">Sailing</div>
                                    <div class="text-center {{ $booking->status >= $status::ARRIVED ? 'text-indigo-600' : 'text-gray-400' }}">Arrived</div>
                                    <div class="text-center {{ $booking->status >= $status::COMPLETED ? 'text-indigo-600' : 'text-gray-400' }}">Completed</div>
                                </div>
                            @endif
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
                        @if($booking->editAfterTelex->count() > 0 && !$booking->enable_edit)
                        @php
                            $editCount = $booking->editAfterTelex->count();
                            $latestEdit = $booking->editAfterTelex->sortByDesc('created_at')->first();
                        @endphp
                        <div class="mt-2" onclick="document.getElementById('show-edit-history-modal').classList.remove('hidden')">
                            <x-alert-instruction
                                message="This booking has been edited {{ $editCount }} times after BL confirmed. The latest edit was on {{ $latestEdit->created_at->format('d-m-Y') }} by {{ $latestEdit->edited_by }}"
                                action_text="View Edit History"
                                action_url="#"
                            />
                        </div>
                        @endif
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
                            @elseif($booking->status == 4 && $booking->enable_edit && auth()->user()->role != 'customer')
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
                                        <span class="text-sm italic text-red-500">Assigned by GUS</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Voyage Number</p>
                                <p class="font-medium">
                                    @if (!empty($booking->voyage->voyage_number))
                                        {{ $booking->voyage->voyage_number }}
                                        @if (session('warning'))
                                            <p class="text-xs font-medium text-amber-800">{{ session('warning') }}</p>
                                        @endif
                                    @else
                                        <span class="text-sm italic text-red-500">Assigned by GUS</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tug</p>
                                <p class="font-medium">
                                    @if (!empty($booking->tug))
                                        {{ $booking->tug }}
                                    @else
                                        <span class="text-sm italic text-red-500">Assigned by GUS</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Delivery Terms</p>
                                <p class="font-medium">
                                    @if (!empty($booking->delivery_terms))
                                        {{ $booking->delivery_terms }}
                                    @else
                                        <span class="text-sm italic text-red-500">Assigned by GUS</span>
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
                                        <span class="text-sm italic text-red-500">Assigned by GUS</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Estimated Time of Arrival</p>
                                <p class="font-medium">
                                    @if (!empty($booking->eta))
                                        {{ $booking->eta->format('Y-m-d H:i') }}
                                    @else
                                        <span class="text-sm italic text-red-500">Assigned by GUS</span>
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
                            @if($booking->status > 1 && $booking->status < 4)
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
                            @elseif($booking->status == 4 && $booking->enable_edit && auth()->user()->role != 'customer')
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
                        <!-- Show empty shipping instructions card if no shipping instructions are added. temporary deactivated -->
                            <!-- <div class="rounded-md bg-yellow-50 p-4">
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
                            </div> -->
                        @else
                            <div class="space-y-4">
                                @foreach($booking->shippingInstructions as $si)
                                    <div class="border rounded p-4 bg-white" data-si-id="{{ $si->id }}" data-request-ids="{{ $si->changeRequests()->orderBy('created_at', 'desc')->pluck('id')->toJson() }}">

                                        <!-- Customer change request status badge -->
                                        @php
                                            // Fetch the most recent active request for THIS SI
                                            $activeReq = $si->changeRequests()
                                                ->whereNotIn('status', [
                                                    \App\Models\SiChangeRequest::STATUS_APPROVED_APPLIED,
                                                    \App\Models\SiChangeRequest::STATUS_REJECTED,
                                                    \App\Models\SiChangeRequest::STATUS_CANCELLED,
                                                    \App\Models\SiChangeRequest::STATUS_EXPIRED,
                                                ])
                                                ->latest()   // created_at desc
                                                ->first();

                                            // Get all change requests for this SI (for timeline)
                                            $allChangeRequests = $si->changeRequests()
                                                ->orderBy('created_at', 'desc')
                                                ->get();

                                            // Map status -> label + color style
                                            $statusMeta = [
                                                \App\Models\SiChangeRequest::STATUS_UNDER_REVIEW => [
                                                    'label' => 'Change Request: Under Review',
                                                    'classes' => 'bg-amber-100 text-amber-800 ring-1 ring-inset ring-amber-200',
                                                ],
                                                \App\Models\SiChangeRequest::STATUS_APPROVED_FOR_EDIT => [
                                                    'label' => 'Change Request: Approved For Edit',
                                                    'classes' => 'bg-blue-100 text-blue-800 ring-1 ring-inset ring-blue-200',
                                                ],
                                                \App\Models\SiChangeRequest::STATUS_PENDING_FINAL_REVIEW => [
                                                    'label' => 'Change Request: Pending Final Review',
                                                    'classes' => 'bg-indigo-100 text-indigo-800 ring-1 ring-inset ring-indigo-200',
                                                ],
                                            ];
                                        @endphp

                                        @if($activeReq)
                                            <div class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                                <div class="flex items-center justify-between mb-3">
                                                    <span class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-full {{ $statusMeta[$activeReq->status]['classes'] ?? 'bg-slate-100 text-slate-800 ring-1 ring-inset ring-slate-200' }}">
                                                        <span class="inline-block w-2 h-2 rounded-full bg-current opacity-75 animate-pulse"></span>
                                                        {{ $statusMeta[$activeReq->status]['label'] ?? 'Change Request: Active' }}
                                                    </span>

                                                    <button type="button"
                                                        onclick="openSiTimelineModal({{ $si->id }})"
                                                        class="inline-flex items-center gap-1.5 text-xs font-medium text-indigo-600 hover:text-indigo-700 transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        View Timeline
                                                    </button>
                                                </div>

                                                {{-- Action Buttons Section --}}
                                                <div class="flex flex-wrap items-center gap-2 mt-3 pt-3 border-t border-gray-200">
                                                    @php
                                                        $isCustomer = auth()->check() && auth()->user()->role === 'customer';
                                                        $ownsBooking = $isCustomer && (int)$booking->user_id === (int)auth()->id();
                                                        $isAdmin = auth()->check() && auth()->user()->role !== 'customer';
                                                    @endphp

                                                    {{-- Customer Actions --}}
                                                    @if($isCustomer && $ownsBooking)
                                                        @if($activeReq->status === \App\Models\SiChangeRequest::STATUS_APPROVED_FOR_EDIT)
                                                            <a href="{{ route('si-change-requests.edit-approved', [$si, $activeReq]) }}"
                                                                class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-indigo-700 transition-colors">
                                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                                Edit Approved Fields
                                                            </a>
                                                            
                                                            <button type="button"
                                                                onclick="openSiCancelModal({{ $activeReq->id }})"
                                                                class="inline-flex items-center gap-1.5 rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-red-700 transition-colors">
                                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                                Cancel Request
                                                            </button>
                                                        @endif
                                                    @endif

                                                    {{-- Admin Actions --}}
                                                    @if($isAdmin)
                                                        @if($activeReq->status === \App\Models\SiChangeRequest::STATUS_UNDER_REVIEW)
                                                            <button type="button"
                                                                onclick="openSiApproveModal({{ $activeReq->id }})"
                                                                class="inline-flex items-center gap-1.5 rounded-lg bg-amber-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-amber-700 transition-colors">
                                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                Review Request
                                                            </button>
                                                        @elseif($activeReq->status === \App\Models\SiChangeRequest::STATUS_PENDING_FINAL_REVIEW)
                                                            <button type="button"
                                                                onclick="openSiFinalModal({{ $activeReq->id }})"
                                                                class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-indigo-700 transition-colors">
                                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                Final Review
                                                            </button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($allChangeRequests->isNotEmpty())
                                            <div class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200 flex items-center justify-end">
                                                <button type="button"
                                                    onclick="openSiTimelineModal({{ $si->id }})"
                                                    class="inline-flex items-center gap-1.5 text-xs font-medium text-indigo-600 hover:text-indigo-700 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    View Timeline
                                                </button>
                                            </div>
                                        @endif
                                        <!-- End of Customer change request status badge -->

                                        <!-- Customer Cancel Modal -->
                                        @php
                                            $isCustomer = auth()->check() && auth()->user()->role === 'customer';
                                            $ownsBooking = $isCustomer && (int)$booking->user_id === (int)auth()->id();
                                        @endphp
                                        @if($activeReq
                                            && $activeReq->status === \App\Models\SiChangeRequest::STATUS_APPROVED_FOR_EDIT
                                            && $ownsBooking)
                                            <div id="si-cancel-modal-{{ $activeReq->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
                                                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeSiCancelModal({{ $activeReq->id }})"></div>

                                                <div class="relative w-full max-w-lg rounded-xl bg-white shadow-2xl">
                                                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                                                        <h3 class="text-lg font-semibold text-gray-900">Cancel change request</h3>
                                                        <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeSiCancelModal({{ $activeReq->id }})">
                                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M6 18L18 6M6 6l12 12"/>
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <form method="POST" action="{{ route('si-change-requests.customer-cancel', $activeReq) }}" class="p-6 space-y-5">
                                                        @csrf
                                                        @method('PATCH')

                                                        <div class="rounded-lg bg-red-50 p-4 border border-red-200">
                                                            <div class="flex items-start gap-3">
                                                                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                </svg>
                                                                <p class="text-sm text-red-800 leading-relaxed">
                                                                    You are about to cancel this change request. You will need to submit a new request if you want to add or modify fields again.
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Reason for cancellation</label>
                                                            <textarea name="cancel_reason" rows="4"
                                                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition-colors"
                                                                    placeholder="e.g., I need to request additional fields together..." required></textarea>
                                                        </div>

                                                        <div class="flex justify-end gap-3 border-t border-gray-200 pt-5">
                                                            <button type="button"
                                                                    onclick="closeSiCancelModal({{ $activeReq->id }})"
                                                                    class="inline-flex items-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                                                Keep Request
                                                            </button>
                                                            <button type="submit"
                                                                    class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700 transition-colors">
                                                                Confirm Cancel
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Admin action button for change request -->
                                        @php
                                            $isAdmin = auth()->check() && auth()->user()->role !== 'customer';
                                        @endphp
                                        
                                        @if($activeReq && $activeReq->status === \App\Models\SiChangeRequest::STATUS_UNDER_REVIEW && $isAdmin)
                                            <!-- Admin Approve Modal -->
                                            <div id="si-approve-modal-{{ $activeReq->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
                                                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="closeSiApproveModal({{ $activeReq->id }})"></div>

                                                <div class="relative w-full max-w-2xl rounded-xl bg-white shadow-2xl max-h-[90vh] overflow-hidden flex flex-col">
                                                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-amber-100/50">
                                                        <div>
                                                            <h3 class="text-lg font-semibold text-gray-900">Review Change Request</h3>
                                                            <p class="text-xs text-gray-600 mt-0.5">Approve fields or reject the request</p>
                                                        </div>
                                                        <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeSiApproveModal({{ $activeReq->id }})">
                                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M6 18L18 6M6 6l12 12"/>
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <div class="overflow-y-auto flex-1 p-6 space-y-6">
                                                        <!-- Customer's Reason -->
                                                        @if(!empty($activeReq->reason))
                                                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                                                <div class="flex items-center gap-2 mb-2">
                                                                    <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                                    </svg>
                                                                    <p class="text-sm font-semibold text-gray-900">Customer's Reason for Change</p>
                                                                </div>
                                                                <p class="text-sm text-gray-700 leading-relaxed mt-2">{{ $activeReq->reason }}</p>
                                                            </div>
                                                        @endif

                                                        <!-- Approve Fields Form -->
                                                        <form method="POST" action="{{ route('si-change-requests.approve-fields', $activeReq) }}" id="approve-form-{{ $activeReq->id }}" class="space-y-5">
                                                            @csrf
                                                            @method('PATCH')

                                                            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                                                <div class="flex items-center gap-2 mb-3">
                                                                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                    </svg>
                                                                    <p class="text-sm font-semibold text-blue-900">Customer requested fields</p>
                                                                </div>
                                                                <div class="grid grid-cols-2 gap-3 max-h-56 overflow-y-auto pr-2 border border-blue-200 rounded-md p-4 bg-white">
                                                                    @foreach(($activeReq->requested_fields ?? []) as $field)
                                                                        <label class="flex items-center gap-2.5 px-3 py-2 rounded-md hover:bg-blue-50 transition-colors cursor-pointer">
                                                                            <input type="checkbox" name="approved_fields[]" value="{{ $field }}"
                                                                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500"
                                                                                checked>
                                                                            <span class="text-sm text-gray-700 capitalize">{{ str_replace('_',' ', $field) }}</span>
                                                                        </label>
                                                                    @endforeach
                                                                </div>
                                                                <p class="mt-3 text-xs text-blue-700">Uncheck any fields you do not want to approve.</p>
                                                            </div>

                                                            <div>
                                                                <label class="block text-sm font-semibold text-gray-700 mb-2">Note to customer <span class="text-gray-400 font-normal">(optional)</span></label>
                                                                <textarea name="approver_note" rows="3"
                                                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition-colors"
                                                                        placeholder="Explain any restrictions or instructions..."></textarea>
                                                            </div>

                                                            <div class="flex justify-end pt-4 border-t border-gray-200">
                                                                <button type="submit"
                                                                        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition-colors">
                                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                    </svg>
                                                                    Approve Fields
                                                                </button>
                                                            </div>
                                                        </form>

                                                        <!-- Reject Form -->
                                                        <div class="border-t-2 border-red-200 pt-6 mt-6">
                                                            <div class="bg-red-50 rounded-lg p-4 border border-red-200 mb-4">
                                                                <div class="flex items-center gap-2">
                                                                    <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                    </svg>
                                                                    <p class="text-sm font-semibold text-red-900">Reject this request</p>
                                                                </div>
                                                            </div>
                                                            <form method="POST" action="{{ route('si-change-requests.reject', $activeReq) }}" id="reject-form-{{ $activeReq->id }}" class="space-y-4">
                                                                @csrf
                                                                @method('PATCH')

                                                                <div>
                                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                                        Reason for rejection
                                                                        <span class="text-red-500 ml-1">*</span>
                                                                    </label>
                                                                    <textarea name="rejection_note" rows="4" required
                                                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-2 focus:ring-red-500 text-sm transition-colors"
                                                                            placeholder="Please provide a detailed reason for rejecting this change request..."></textarea>
                                                                    <p class="mt-2 text-xs text-gray-600">This reason will be shared with the customer.</p>
                                                                </div>

                                                                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                                                                    <button type="button"
                                                                            onclick="closeSiApproveModal({{ $activeReq->id }})"
                                                                            class="inline-flex items-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                                                        Close
                                                                    </button>
                                                                    <button type="submit"
                                                                            onclick="return confirm('Are you sure you want to reject this change request? This action cannot be undone.');"
                                                                            class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700 transition-colors">
                                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                        </svg>
                                                                        Reject Request
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <!-- End of Admin action button for change request -->

                                        <!-- Admin final approve button for change request -->
                                        @if($activeReq && $activeReq->status === \App\Models\SiChangeRequest::STATUS_PENDING_FINAL_REVIEW && $isAdmin)
                                            <!-- Final Review Modal -->
                                            <div id="si-final-modal-{{ $activeReq->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
                                                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeSiFinalModal({{ $activeReq->id }})"></div>

                                                <div class="relative w-full max-w-4xl rounded-xl bg-white shadow-2xl max-h-[90vh] overflow-hidden flex flex-col">
                                                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-indigo-100/50">
                                                    <div>
                                                        <h3 class="text-lg font-semibold text-gray-900">Final Review: Draft vs Original</h3>
                                                        <p class="text-xs text-gray-600 mt-0.5">Compare changes and make final decision</p>
                                                    </div>
                                                    <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeSiFinalModal({{ $activeReq->id }})">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    </button>
                                                </div>

                                                @php
                                                    $labels = [
                                                    'shipper' => 'Shipper', 'shipper_address'=>'Shipper Address',
                                                    'consignee'=>'Consignee','consignee_address'=>'Consignee Address',
                                                    'notify_party'=>'Notify Party','notify_party_address'=>'Notify Party Address',
                                                    'cargo_description'=>'Cargo Description','hs_code'=>'HS Code',
                                                    'gross_weight'=>'Gross Weight','volume'=>'Volume','box_operator'=>'Box Operator',
                                                    'containers'=>'Containers List',
                                                    ];
                                                    $pre  = collect($activeReq->prechange_snapshot ?? []);
                                                    $draft= collect($activeReq->draft_changes ?? []);
                                                    $approved = collect($activeReq->approved_fields ?? []);
                                                    $keys = $approved->all(); // only approved fields are relevant
                                                @endphp

                                                <div class="overflow-y-auto flex-1 p-6">
                                                    <div class="space-y-5">
                                                    @forelse($keys as $field)
                                                        @php
                                                        $before = $pre->get($field);
                                                        $after  = $draft->get($field);
                                                        
                                                        // Special handling for containers - show diff instead of full list
                                                        if ($field === 'containers') {
                                                            // Normalize containers to a comparable format (key by container_number)
                                                            $normalizeContainers = function($containers) {
                                                                if (empty($containers) || !is_array($containers)) {
                                                                    return [];
                                                                }
                                                                
                                                                $normalized = [];
                                                                foreach ($containers as $cargoId => $containerGroup) {
                                                                    // Handle both formats: indexed array or cargo_id keyed array
                                                                    if (isset($containerGroup['container_number']) || isset($containerGroup['cargo_id'])) {
                                                                        $containerGroup = [$containerGroup];
                                                                    }
                                                                    
                                                                    foreach ($containerGroup as $container) {
                                                                        $cargoId = $container['cargo_id'] ?? $cargoId;
                                                                        $containerNum = trim($container['container_number'] ?? '');
                                                                        if (!empty($containerNum)) {
                                                                            $normalized[$containerNum] = [
                                                                                'cargo_id' => (int)$cargoId,
                                                                                'container_number' => $containerNum,
                                                                                'seal_number' => trim($container['seal_number'] ?? ''),
                                                                            ];
                                                                        }
                                                                    }
                                                                }
                                                                return $normalized;
                                                            };
                                                            
                                                            $beforeNormalized = $normalizeContainers($before ?? []);
                                                            $afterNormalized = $normalizeContainers($after ?? []);
                                                            
                                                            // Find differences
                                                            $added = [];
                                                            $deleted = [];
                                                            $modified = [];
                                                            
                                                            foreach ($afterNormalized as $containerNum => $afterContainer) {
                                                                if (!isset($beforeNormalized[$containerNum])) {
                                                                    $added[$containerNum] = $afterContainer;
                                                                } elseif (
                                                                    $beforeNormalized[$containerNum]['seal_number'] !== $afterContainer['seal_number'] ||
                                                                    $beforeNormalized[$containerNum]['cargo_id'] !== $afterContainer['cargo_id']
                                                                ) {
                                                                    $modified[$containerNum] = [
                                                                        'before' => $beforeNormalized[$containerNum],
                                                                        'after' => $afterContainer,
                                                                    ];
                                                                }
                                                            }
                                                            
                                                            foreach ($beforeNormalized as $containerNum => $beforeContainer) {
                                                                if (!isset($afterNormalized[$containerNum])) {
                                                                    $deleted[$containerNum] = $beforeContainer;
                                                                }
                                                            }
                                                            
                                                            $hasChanges = !empty($added) || !empty($deleted) || !empty($modified);
                                                        } else {
                                                            // format arrays (addresses) nicely
                                                            $fmt = fn($v) => is_array($v) ? implode("\n", $v) : (string)($v ?? '');
                                                            $fmtBefore = $fmt($before);
                                                            $fmtAfter = $fmt($after);
                                                        }
                                                        @endphp

                                                        <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                                                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                                                            <h4 class="text-sm font-semibold text-gray-900">{{ $labels[$field] ?? ucfirst(str_replace('_',' ', $field)) }}</h4>
                                                        </div>
                                                        
                                                        @if($field === 'containers')
                                                            <div class="p-4 space-y-4">
                                                                @if(!$hasChanges)
                                                                    <p class="text-sm text-gray-500">No container changes detected.</p>
                                                                @else
                                                                    {{-- Added Containers --}}
                                                                    @if(!empty($added))
                                                                        <div>
                                                                            <div class="text-xs font-semibold text-green-700 mb-2 flex items-center gap-1">
                                                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                                                </svg>
                                                                                Added ({{ count($added) }})
                                                                            </div>
                                                                            <div class="bg-green-50 rounded-md p-3 space-y-1">
                                                                                @foreach($added as $container)
                                                                                    <div class="text-sm text-green-800">
                                                                                        <strong>{{ $container['container_number'] }}</strong> / {{ $container['seal_number'] ?: 'N/A' }}
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    
                                                                    {{-- Deleted Containers --}}
                                                                    @if(!empty($deleted))
                                                                        <div>
                                                                            <div class="text-xs font-semibold text-red-700 mb-2 flex items-center gap-1">
                                                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                                </svg>
                                                                                Deleted ({{ count($deleted) }})
                                                                            </div>
                                                                            <div class="bg-red-50 rounded-md p-3 space-y-1">
                                                                                @foreach($deleted as $container)
                                                                                    <div class="text-sm text-red-800">
                                                                                        <strong>{{ $container['container_number'] }}</strong> / {{ $container['seal_number'] ?: 'N/A' }}
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    
                                                                    {{-- Modified Containers --}}
                                                                    @if(!empty($modified))
                                                                        <div>
                                                                            <div class="text-xs font-semibold text-amber-700 mb-2 flex items-center gap-1">
                                                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                                </svg>
                                                                                Modified ({{ count($modified) }})
                                                                            </div>
                                                                            <div class="space-y-2">
                                                                                @foreach($modified as $containerNum => $change)
                                                                                    <div class="border rounded-md overflow-hidden">
                                                                                        <div class="bg-amber-50 px-3 py-2 border-b">
                                                                                            <span class="text-sm font-medium text-amber-900">Container: <strong>{{ $containerNum }}</strong></span>
                                                                                        </div>
                                                                                        <div class="grid grid-cols-2 gap-0">
                                                                                            <div class="p-3 text-sm bg-red-50">
                                                                                                <div class="text-gray-500 text-xs mb-1">Before</div>
                                                                                                <div class="text-red-800">
                                                                                                    Seal: {{ $change['before']['seal_number'] ?: 'N/A' }}
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="p-3 text-sm bg-green-50">
                                                                                                <div class="text-gray-500 text-xs mb-1">After</div>
                                                                                                <div class="text-green-800">
                                                                                                    Seal: {{ $change['after']['seal_number'] ?: 'N/A' }}
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        @else
                                                            <div class="grid grid-cols-2 gap-0">
                                                                <div class="p-3 text-sm">
                                                                <div class="text-gray-500 text-xs mb-1">Before</div>
                                                                <pre class="bg-red-50 text-red-800 rounded p-2 whitespace-pre-wrap">{{ $fmtBefore }}</pre>
                                                                </div>
                                                                <div class="p-3 text-sm">
                                                                <div class="text-gray-500 text-xs mb-1">After (Draft)</div>
                                                                <pre class="bg-green-50 text-green-800 rounded p-2 whitespace-pre-wrap">{{ $fmtAfter }}</pre>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        </div>
                                                    @empty
                                                        <p class="text-sm text-gray-600">No fields to review.</p>
                                                    @endforelse
                                                    </div>

                                                    <form method="POST" action="{{ route('si-change-requests.final-decide', $activeReq) }}" class="mt-6 space-y-4 border-t-2 border-gray-200 pt-6">
                                                        @csrf
                                                        @method('PATCH')

                                                        <div>
                                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Final note <span class="text-red-500">*</span></label>
                                                            <textarea name="final_note" rows="3"
                                                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-sm transition-colors"
                                                                    placeholder="Add any note for audit / customer..." required></textarea>
                                                            <p class="mt-2 text-xs text-gray-500">This note will be recorded for audit purposes.</p>
                                                        </div>

                                                        <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                                                            <button type="button"
                                                                    onclick="closeSiFinalModal({{ $activeReq->id }})"
                                                                    class="inline-flex items-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                                                                Close
                                                            </button>

                                                            <div class="flex gap-3">
                                                                <button type="submit" name="decision" value="reject"
                                                                        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700 transition-colors"
                                                                        onclick="return confirm('Reject this change request?');">
                                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                    Reject
                                                                </button>

                                                                <button type="submit" name="decision" value="approve"
                                                                        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition-colors">
                                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                    </svg>
                                                                    Approve & Apply
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                </div>
                                            </div>
                                            @endif
                                            <!-- End of Admin final approve button for change request -->


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
                                            <div class="space-y-2">
                                                @foreach($si->containers->groupBy('cargo.container_type') as $type => $containers)
                                                    <div class="text-sm">
                                                        <span class="font-medium">{{ $type }}:</span> 
                                                        {{ $containers->count() }} container{{ $containers->count() > 1 ? 's' : '' }}
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

                                            <!-- New customer change request function -->
                                            @php
                                                // Preconditions
                                                $isCustomer = auth()->user()->role === 'customer';
                                                $isBlConfirmed = $booking->status == $status::BL_CONFIRMED;

                                                // All SIs telex released?
                                                $allTelexReleased = $booking->shippingInstructions->every(function($s) {
                                                    return (bool) $s->telex_bl_released;
                                                });

                                                // No active request for THIS SI?
                                                // (active = not in terminal statuses)
                                                $hasActiveRequest = $si->changeRequests()
                                                    ->whereNotIn('status', [
                                                        \App\Models\SiChangeRequest::STATUS_APPROVED_APPLIED,
                                                        \App\Models\SiChangeRequest::STATUS_REJECTED,
                                                        \App\Models\SiChangeRequest::STATUS_CANCELLED,
                                                        \App\Models\SiChangeRequest::STATUS_EXPIRED,
                                                    ])
                                                    ->exists();

                                                $canRequestChange = $isCustomer && $isBlConfirmed && $allTelexReleased && !$hasActiveRequest;
                                            @endphp

                                            @if($canRequestChange)
                                                <button type="button"
                                                onclick="openSiChangeModal({{ $si->id }})"
                                                class="ml-4 my-4 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-indigo-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Request Change
                                                </button>
                                            @endif
                                            
                                            <!-- Customer change request form -->
                                            @php
                                                // Minimal field options to start (adjust later as needed)
                                                $siFieldOptions = [
                                                    'shipper'              => 'Shipper',
                                                    'shipper_address'      => 'Shipper Address',
                                                    'consignee'            => 'Consignee',
                                                    'consignee_address'    => 'Consignee Address',
                                                    'notify_party'         => 'Notify Party',
                                                    'notify_party_address' => 'Notify Party Address',
                                                    'cargo_description'    => 'Cargo Description',
                                                    'hs_code'              => 'HS Code',
                                                    'gross_weight'         => 'Gross Weight',
                                                    'volume'               => 'Volume',
                                                    'containers'           => 'Containers List',
                                                ];
                                            @endphp

                                            <div id="si-change-modal-{{ $si->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
                                                <!-- backdrop -->
                                                <div class="absolute inset-0 bg-gradient-to-br from-gray-900/60 to-gray-900/40 backdrop-blur-sm transition-opacity" onclick="closeSiChangeModal({{ $si->id }})"></div>

                                                <!-- dialog -->
                                                <div class="relative w-full max-w-2xl transform transition-all">
                                                    <div class="relative rounded-2xl bg-white shadow-2xl ring-1 ring-black/5">
                                                        <!-- Header -->
                                                        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                                                            <div>
                                                                <h3 class="text-lg font-semibold text-gray-900">Request Shipping Instruction Change</h3>
                                                                <p class="text-sm text-gray-500 mt-0.5">Submit changes for review and approval</p>
                                                            </div>
                                                            <button class="flex items-center justify-center w-8 h-8 rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors" onclick="closeSiChangeModal({{ $si->id }})">
                                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                </svg>
                                                            </button>
                                                        </div>

                                                        <!-- Warning -->
                                                        <div class="px-6 pt-5">
                                                            <div class="relative rounded-xl bg-gradient-to-r from-amber-50 to-yellow-50 p-4 border border-amber-200/60 shadow-sm">
                                                                <div class="flex gap-3">
                                                                    <div class="flex-shrink-0">
                                                                        <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                    </div>
                                                                    <div class="flex-1">
                                                                        <h4 class="text-sm font-semibold text-amber-900 mb-1">Important Notice</h4>
                                                                        <p class="text-sm text-amber-800 leading-relaxed">
                                                                            Telex BL has been released. Approved changes may incur an additional fee.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Form (test-mode: previews only) -->
                                                        <form method="POST" action="{{ route('si-change-requests.store', $si) }}" class="px-6 py-5 space-y-6">
                                                            @csrf
                                                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                                            <input type="hidden" name="shipping_instruction_id" value="{{ $si->id }}">

                                                            <!-- Fields checklist -->
                                                            <div>
                                                                <label class="block text-sm font-semibold text-gray-900 mb-3">
                                                                    Select fields to change
                                                                    <span class="text-gray-400 font-normal ml-1">(check all that apply)</span>
                                                                </label>
                                                                <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4">
                                                                    <div class="grid grid-cols-2 gap-3 max-h-56 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent">
                                                                        @foreach($siFieldOptions as $key => $label)
                                                                            <label class="group flex items-center gap-3 px-3 py-2.5 rounded-lg bg-white border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/50 cursor-pointer transition-all duration-200">
                                                                                <input type="checkbox"
                                                                                    name="requested_fields[]"
                                                                                    value="{{ $key }}"
                                                                                    class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-0 transition-colors cursor-pointer">
                                                                                <span class="text-sm text-gray-700 group-hover:text-indigo-900 font-medium select-none">{{ $label }}</span>
                                                                            </label>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Reason -->
                                                            <div>
                                                                <label class="block text-sm font-semibold text-gray-900 mb-3">
                                                                    Reason for change
                                                                    <span class="text-red-500 ml-0.5">*</span>
                                                                </label>
                                                                <textarea name="reason" rows="4"
                                                                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-0 text-sm placeholder-gray-400 transition-colors resize-none"
                                                                        placeholder="Please provide a detailed explanation of what needs to be changed and why..." required></textarea>
                                                                <p class="mt-2 text-xs text-gray-500">Be as specific as possible to help expedite the approval process.</p>
                                                            </div>

                                                            <div class="flex items-center justify-end gap-3 pt-5 border-t border-gray-100">
                                                                <button type="button"
                                                                        class="inline-flex items-center gap-2 rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 hover:ring-gray-400 transition-all duration-200"
                                                                        onclick="closeSiChangeModal({{ $si->id }})">
                                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                    </svg>
                                                                    Cancel
                                                                </button>
                                                                <button type="submit"
                                                                        class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-700 px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:shadow-lg hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200">
                                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                                    </svg>
                                                                    Submit Request
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--End of Customer change request form -->

                                            <!-- Timeline Modal -->
                                            <div id="si-timeline-modal-{{ $si->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
                                                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="closeSiTimelineModal({{ $si->id }})"></div>

                                                <div class="relative w-full max-w-3xl rounded-xl bg-white shadow-2xl max-h-[90vh] overflow-hidden flex flex-col">
                                                    <div class="flex items-center justify-between px-5 py-3 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-indigo-100/50">
                                                        <div>
                                                            <h3 class="text-base font-semibold text-gray-900">Change Request Timeline</h3>
                                                            <p class="text-xs text-gray-600">View all events and changes</p>
                                                        </div>
                                                        <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeSiTimelineModal({{ $si->id }})">
                                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <div class="px-4 py-4 overflow-y-auto flex-1 bg-gray-50/50">
                                                        <div id="si-timeline-content-{{ $si->id }}" class="space-y-4">
                                                            <div class="text-center py-8">
                                                                <div class="inline-block animate-spin rounded-full h-8 w-8 border-3 border-indigo-200 border-t-indigo-600"></div>
                                                                <p class="mt-3 text-sm font-medium text-gray-600">Loading timeline...</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Timeline Modal -->

                                            <p class="text-left italic text-red-500 text-sm">
                                                @php
                                                    $freeRevisionsLimit = 3;
                                                    $remainingFreeRevisions = max(0, $freeRevisionsLimit - $si->post_bl_edit_count);
                                                @endphp

                                                @if(!$si->telex_bl_released)
                                                    <span class="{{ $remainingFreeRevisions > 0 ? 'text-green-500' : 'text-red-500' }}">
                                                        Remaining free revisions: {{ $remainingFreeRevisions }} of {{ $freeRevisionsLimit }}
                                                    </span>
                                                    <br>
                                                    Total SI Revisions after BL confirmed: {{ $si->post_bl_edit_count }}
                                                @else
                                                    <span class="text-red-500">
                                                        Revision(s) requested: {{ $si->number_of_revisions_requested }}
                                                    </span>
                                                    <br>
                                                    <span class="text-green-500">
                                                        Revision(s) applied: {{ $si->number_of_revisions_applied }}
                                                    </span>
                                                @endif
                                                
                                            </p>
                                            @if ($booking->enable_edit)
                                                @php $isCustomer = auth()->user()->role === 'customer'; @endphp

                                                @if (!$si->telex_bl_released || ($si->telex_bl_released && !$isCustomer))
                                                    @if ($booking->status < 5 && $remainingFreeRevisions > 0)
                                                        <a href="{{ route('shipping-instructions.show', $si) }}"
                                                            class="text-indigo-600 hover:text-indigo-900">
                                                            Edit
                                                        </a>
                                                    @elseif ($booking->status < 5 && $remainingFreeRevisions <= 0)
                                                        <a href="#"
                                                            onclick="showRevisionWarning(event, '{{ $si->id }}')"
                                                            class="text-indigo-600 hover:text-indigo-900">
                                                            Edit
                                                        </a>
                                                    @endif
                                                @endif
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
                                            @elseif($booking->status == 4 && $booking->enable_edit && auth()->user()->role != 'customer')
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
                                        <option value="K4" {{ old('document_type') == 'k4' ? 'selected' : '' }}>K4</option>
                                        <option value="K5" {{ old('document_type') == 'k5' ? 'selected' : '' }}>K5</option>
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
                                                        $allowedTypes = ['Manifest', 'Container Load List', 'Notice of Arrival', 'K4', 'K5'];
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
                                        @if($booking->shippingInstructions->isNotEmpty())
                                            bg-blue-600 text-white hover:bg-blue-700
                                        @else
                                            bg-gray-300 text-gray-500 cursor-not-allowed
                                        @endif"
                                    @if($booking->shippingInstructions->isEmpty()) disabled @endif>
                                    Submit SI 
                                </button>

                                <!-- Tooltip -->
                                <div x-show="showTooltip" 
                                    x-transition
                                    class="absolute bottom-full mb-2 w-64 p-2 bg-gray-800 text-white text-xs rounded shadow-lg"
                                    style="left: 10%; transform: translateX(-50%)">
                                    @if($booking->shippingInstructions->isEmpty())
                                        Please add at least one shipping instruction.
                                    @else
                                        Submit shipping instructions.
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

                                    @if(!$booking->enable_edit)
                                    <div class="relative">
                                        <button type="button"
                                            onclick="document.getElementById('enable-edit-confirmation-modal').classList.remove('hidden')"
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest 
                                                bg-red-600 text-white hover:bg-red-700">
                                            Enable Edit
                                        </button>
                                    </div>
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
                                            onclick="document.getElementById('disable-edit-confirmation-modal').classList.remove('hidden')"
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest 
                                                bg-green-600 text-white hover:bg-green-700">
                                            Disable Edit
                                        </button>
                                    </div>
                                    <div class="relative">
                                        <button type="button"
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest 
                                                bg-gray-300 text-gray-500 cursor-not-allowed">
                                        Sailing
                                    </button>
                                    @endif

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
                            <div id="telex-bl-release-modal" class="hidden relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
                                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                        <div class="relative transform overflow-hidden rounded-lg bg-white px-6 pb-6 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                                            <div>
                                                <div class="mt-3 text-left sm:mt-5">
                                                    <h3 class="text-base font-semibold text-gray-900" id="modal-title">Telex BL Release</h3>
                                                    <div class="mt-4 text-sm text-gray-700 space-y-2">
                                                        <p class="font-semibold text-yellow-600">⚠️ IMPORTANT: Please Read Before Clicking 'Telex Release'</p>
                                                        <p>Once you click <strong>Telex Release</strong>, all booking details will be considered final and confirmed.</p>
                                                        <ul class="list-disc list-outside pl-5 space-y-1">
                                                            <li>
                                                                If any items are still <strong>unconfirmed</strong>, do <strong class="text-red-600">NOT</strong> proceed.
                                                            </li>
                                                            <li>
                                                                After submission, <strong class="text-red-600">no adjustments</strong> can be made.
                                                            </li>
                                                            <li>
                                                                Any changes will require <strong>cancellation of the current booking</strong> and 
                                                                <strong class="text-red-600">resubmission</strong> of a new booking.
                                                            </li>
                                                        </ul>
                                                        <p class="font-semibold">Please ensure all information is correct and confirmed before proceeding.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-6 flex justify-end gap-3">
                                                <button type="button" onclick="document.getElementById('telex-bl-release-modal').classList.add('hidden')"
                                                    class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                                    Cancel
                                                </button>
                                                <button type="button" id="confirm-telex-bl-release"
                                                    class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                                    Confirm
                                                </button>
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

                            <!-- Rejected SI Change Request Modal -->
                            @if($rejectedRequest)
                            <div id="rejected-request-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
                                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="closeRejectedRequestModal()"></div>
                                
                                <div class="relative w-full max-w-2xl transform transition-all">
                                    <div class="relative rounded-2xl bg-white shadow-2xl ring-1 ring-black/5">
                                        <!-- Header -->
                                        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                                            <div class="flex items-center gap-3">
                                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-red-100">
                                                    <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h3 class="text-lg font-semibold text-gray-900">SI Change Request Rejected</h3>
                                                    <p class="text-sm text-gray-500">Your previous change request has been rejected</p>
                                                </div>
                                            </div>
                                            <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeRejectedRequestModal()">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Body -->
                                        <div class="px-6 py-5 space-y-4">
                                            <!-- Shipping Instruction Info -->
                                            @if($rejectedRequest->shippingInstruction)
                                            <div class="bg-gray-50 rounded-lg p-4">
                                                <p class="text-sm font-medium text-gray-700 mb-1">Shipping Instruction</p>
                                                <p class="text-sm text-gray-900">SI #{{ $rejectedRequest->shippingInstruction->sub_booking_number }}</p>
                                            </div>
                                            @endif

                                            <!-- Requested Fields -->
                                            @if(!empty($rejectedRequest->requested_fields))
                                            <div>
                                                <p class="text-sm font-medium text-gray-700 mb-2">Requested Fields</p>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($rejectedRequest->requested_fields as $field)
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ str_replace('_', ' ', ucwords($field, '_')) }}
                                                    </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif

                                            <!-- Your Reason -->
                                            @if($rejectedRequest->reason)
                                            <div>
                                                <p class="text-sm font-medium text-gray-700 mb-2">Your Reason for Change</p>
                                                <div class="bg-gray-50 rounded-lg p-3">
                                                    <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $rejectedRequest->reason }}</p>
                                                </div>
                                            </div>
                                            @endif

                                            <!-- Rejection Note -->
                                            @if($rejectedRequest->approver_note || $rejectedRequest->final_note)
                                            <div>
                                                <p class="text-sm font-medium text-red-700 mb-2">Rejection Reason</p>
                                                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                                    <p class="text-sm text-red-900 whitespace-pre-wrap">{{ $rejectedRequest->final_note ?? $rejectedRequest->approver_note }}</p>
                                                </div>
                                            </div>
                                            @endif

                                            <!-- Rejected By -->
                                            @if($rejectedRequest->finalReviewer || $rejectedRequest->approver)
                                            <div class="border-t border-gray-200 pt-4">
                                                <div class="flex items-center justify-between text-sm">
                                                    <span class="text-gray-500">Rejected by</span>
                                                    <span class="font-medium text-gray-900">
                                                        {{ $rejectedRequest->finalReviewer->name ?? $rejectedRequest->approver->name ?? 'Administrator' }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center justify-between text-sm mt-1">
                                                    <span class="text-gray-500">Rejected on</span>
                                                    <span class="font-medium text-gray-900">
                                                        {{ $rejectedRequest->final_decision_at ? $rejectedRequest->final_decision_at->format('F d, Y \a\t H:i') : $rejectedRequest->updated_at->format('F d, Y \a\t H:i') }}
                                                    </span>
                                                </div>
                                            </div>
                                            @endif
                                        </div>

                                        <!-- Footer -->
                                        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                                            <button type="button" onclick="closeRejectedRequestModal()"
                                                class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 bg-white border border-gray-300 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Disable Edit Confirmation Modal -->
                            <div id="disable-edit-confirmation-modal" class="hidden relative z-10" aria-labelledby="modal-title"
                                role="dialog" aria-modal="true">
                                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
                                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                    <div
                                        class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                        <div
                                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                            <div>
                                                <div class="mt-3 text-center sm:mt-5">
                                                    <h3 class="text-base font-semibold text-gray-900" id="modal-title">Disable Edit Confirmation</h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">Please confirm that all the information are correct before disabling edit.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-5 flex justify-between items-center sm:mt-6">
                                                <button type="button" onclick="document.getElementById('disable-edit-confirmation-modal').classList.add('hidden')"
                                                    class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancel</button>
                                                <div class="flex gap-3">
                                                    <button type="button" onclick="window.location.href='{{ route('booking.disable-edit', $booking) }}'"
                                                        class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Show Edit History Modal -->
                            @php
                            $fmtVal = function ($v) {
                                if (is_null($v) || $v === '') return '—';
                                if (is_bool($v)) return $v ? 'true' : 'false';
                                if (is_array($v) || is_object($v)) return json_encode($v, JSON_UNESCAPED_UNICODE);
                                return $v;
                            };
                            @endphp

                            <div id="show-edit-history-modal"
                                class="hidden relative z-10"
                                aria-labelledby="modal-title"
                                role="dialog"
                                aria-modal="true">

                            <!-- dark overlay -->
                            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

                            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                <div
                                    class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl
                                        transition-all sm:my-8 sm:w-full sm:max-w-4xl md:max-w-5xl sm:p-8">

                                    <!-- heading -->
                                    <h3 id="modal-title" class="mb-4 text-lg font-semibold text-gray-900 text-center">
                                    Edit History
                                    </h3>

                                    <!-- table wrapper -->
                                    <div class="max-h-[65vh] overflow-y-auto overflow-x-auto border rounded-lg">
                                    <table class="min-w-full text-sm divide-y divide-gray-200">
                                        <thead class="bg-gray-50 sticky top-0 z-10">
                                        <tr>
                                            <th class="px-4 py-2 text-left font-medium text-gray-700 whitespace-nowrap">Request&nbsp;By</th>
                                            <th class="px-4 py-2 text-left font-medium text-gray-700">Request&nbsp;Reason</th>
                                            <th class="px-4 py-2 text-left font-medium text-gray-700 whitespace-nowrap">Edited&nbsp;By</th>
                                            <th class="px-4 py-2 text-left font-medium text-gray-700 whitespace-nowrap">Edited&nbsp;At</th>
                                            <th class="px-4 py-2 text-left font-medium text-gray-700 whitespace-nowrap">Changes</th>
                                        </tr>
                                        </thead>

                                        {{-- One <tbody> per log row so Alpine can scope "open" to both <tr>s --}}
                                        @foreach($booking->editAfterTelex->sortByDesc('created_at') as $edit)
                                        @php
                                            $bkChanges = data_get($edit->changes, 'booking', []);
                                            $siChanges = data_get($edit->changes, 'shipping_instructions', []);
                                            $hasAnyChanges = (!empty($bkChanges)) || (!empty($siChanges));
                                        @endphp

                                        <tbody x-data="{ open: false }" class="divide-y divide-gray-100">
                                            <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 whitespace-nowrap">{{ $edit->request_by }}</td>
                                            <td class="px-4 py-2 whitespace-normal break-words">{{ $edit->request_reason }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap">{{ $edit->edited_by }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap">{{ $edit->created_at->format('d-m-Y H:i:s') }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                @if($hasAnyChanges)
                                                <button type="button"
                                                        @click="open = !open"
                                                        class="inline-flex items-center rounded-md bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700 hover:bg-indigo-100">
                                                    <span x-show="!open">View changes</span>
                                                    <span x-show="open">Hide</span>
                                                </button>
                                                @else
                                                <span class="text-xs text-gray-400">No changes</span>
                                                @endif
                                            </td>
                                            </tr>

                                            {{-- Expanded diff row (shares the same Alpine scope via the <tbody>) --}}
                                            @if($hasAnyChanges)
                                            <tr x-cloak :class="open ? '' : 'hidden'">
                                                <td colspan="6" class="px-4 py-3 bg-gray-50">
                                                {{-- Booking changes --}}
                                                <div class="mb-4">
                                                    <div class="flex items-center gap-2 mb-2">
                                                    <h4 class="font-semibold">Booking</h4>
                                                    @if(empty($bkChanges))
                                                        <span class="text-xs text-gray-500">(no changes)</span>
                                                    @endif
                                                    </div>
                                                    @if(!empty($bkChanges))
                                                    <div class="overflow-x-auto">
                                                        <table class="w-full text-xs border rounded">
                                                        <thead class="bg-white">
                                                            <tr>
                                                            <th class="text-left p-2">Field</th>
                                                            <th class="text-left p-2">From</th>
                                                            <th class="text-left p-2">To</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($bkChanges as $field => $diff)
                                                            <tr class="border-t">
                                                                <td class="p-2">{{ str($field)->replace('.', ' → ')->headline() }}</td>
                                                                <td class="bg-red-50 text-red-700 p-2">{{ is_array($diff['from'] ?? null) ? json_encode($diff['from']) : ($diff['from'] ?? '') }}</td>
                                                                <td class="bg-green-50 text-green-700 p-2">{{ is_array($diff['to'] ?? null) ? json_encode($diff['to']) : ($diff['to'] ?? '') }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                        </table>
                                                    </div>
                                                    @endif
                                                </div>

                                                {{-- Shipping Instruction changes --}}
                                                <div>
                                                    <div class="flex items-center gap-2 mb-2">
                                                    <h4 class="font-semibold">Shipping Instructions</h4>
                                                    @if(empty($siChanges))
                                                        <span class="text-xs text-gray-500">(no changes)</span>
                                                    @endif
                                                    </div>

                                                    @foreach($siChanges as $siId => $row)
                                                    @php
                                                        $type  = data_get($row, 'change_type', 'updated');
                                                        $diffs = data_get($row, 'changes', data_get($row, 'diff', []));
                                                        $isDeleted = isset($diffs['deleted']) || $type === 'deleted';
                                                    @endphp

                                                    <div class="mb-3 rounded border bg-white">
                                                        <div class="flex items-center justify-between p-2">
                                                        <div class="text-xs">
                                                            <span class="font-medium">SI #{{ $booking->shippingInstructions->find($siId)->sub_booking_number ?? $siId }}</span>
                                                        </div>
                                                        <span class="inline-flex items-center rounded px-2 py-0.5 text-[10px] uppercase tracking-wide
                                                                    {{ $type === 'created' ? 'bg-green-100 text-green-800' :
                                                                        ($type === 'deleted' ? 'bg-red-100 text-red-800' :
                                                                        'bg-slate-100 text-slate-800') }}">
                                                            {{ $type }}
                                                        </span>
                                                        </div>

                                                        <div class="p-2 border-t">
                                                        @if($isDeleted)
                                                            <p class="text-xs text-red-700">This Shipping Instruction was deleted during the edit window.</p>
                                                        @else
                                                            @if(!empty($diffs))
                                                            <div class="overflow-x-auto">
                                                                <table class="w-full text-xs">
                                                                <thead>
                                                                    <tr>
                                                                    <th class="text-left p-2">Field</th>
                                                                    <th class="text-left p-2">From</th>
                                                                    <th class="text-left p-2">To</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($diffs as $field => $diff)
                                                                    @if(is_array($diff) && array_key_exists('from', $diff))
                                                                        <tr class="border-t">
                                                                        <td class="p-2">{{ str($field)->replace('.', ' → ')->headline() }}</td>
                                                                        <td class="bg-red-50 text-red-700 p-2">{{ is_array($diff['from'] ?? null) ? json_encode($diff['from']) : ($diff['from'] ?? '') }}</td>
                                                                        <td class="bg-green-50 text-green-700 p-2">{{ is_array($diff['to'] ?? null) ? json_encode($diff['to']) : ($diff['to'] ?? '') }}</td>
                                                                        </tr>
                                                                    @endif
                                                                    @endforeach
                                                                </tbody>
                                                                </table>
                                                            </div>
                                                            @else
                                                            <p class="text-xs text-gray-600">No field changes.</p>
                                                            @endif
                                                        @endif
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                </td>
                                            </tr>
                                            @endif
                                        </tbody>
                                        @endforeach
                                    </table>
                                    </div>

                                    <!-- footer -->
                                    <div class="mt-6 flex justify-center">
                                    <button
                                        type="button"
                                        onclick="document.getElementById('show-edit-history-modal').classList.add('hidden')"
                                        class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white
                                            shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2
                                            focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                        Close
                                    </button>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </div>


                            <!-- Enable Edit Confirmation Modal -->
                            <div id="enable-edit-confirmation-modal" class="hidden relative z-10" aria-labelledby="modal-title"
                                role="dialog" aria-modal="true">
                                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
                                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                    <div
                                        class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                        <div
                                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                            <form action="{{ route('booking.enable-edit', $booking) }}" method="POST">
                                                @csrf
                                                <div class="mt-3 sm:mt-5">
                                                    <h3 class="text-base font-semibold text-gray-900 text-center" id="modal-title">Enable Edit After BL</h3>
                                                    <div class="mt-4 space-y-4">
                                                        <div>
                                                            <label for="request_by" class="block text-sm font-medium text-gray-700">Requested By</label>
                                                            <input type="text" name="request_by" id="request_by" value="{{ $booking->user->name }}" required
                                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                        </div>
                                                        <div>
                                                            <label for="request_date" class="block text-sm font-medium text-gray-700">Request Date</label>
                                                            <input type="date" name="request_date" id="request_date" value="{{ now()->format('Y-m-d') }}" required
                                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                        </div>
                                                        <div>
                                                            <label for="request_reason" class="block text-sm font-medium text-gray-700">Request Reason</label>
                                                            <textarea name="request_reason" id="request_reason" rows="3" required
                                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                                        </div>
                                                        <div>
                                                            <label for="edited_by" class="block text-sm font-medium text-gray-700">Edited By</label>
                                                            <input type="text" name="edited_by" id="edited_by" value="{{ auth()->user()->name }}" required
                                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-5 flex justify-between items-center sm:mt-6">
                                                    <button type="button" onclick="document.getElementById('enable-edit-confirmation-modal').classList.add('hidden')"
                                                        class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancel</button>
                                                    <button type="submit"
                                                        class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Enable Edit</button>
                                                </div>
                                            </form>
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
                                                        <p class="text-sm text-gray-500">Please confirm that all the information are correct before confirming the booking. This action is irreversible.</p>
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

function openSiChangeModal(siId) {
    document.getElementById(`si-change-modal-${siId}`)?.classList.remove('hidden');
}

function closeSiChangeModal(siId) {
    document.getElementById(`si-change-modal-${siId}`)?.classList.add('hidden');
}

/**
 * Test-only: intercept submit and show the payload instead of posting.
 * Next step we'll wire a real POST route.
 */
function previewSiChangeRequest(bookingId, siId) {
    const modal = document.getElementById(`si-change-modal-${siId}`);
    if (!modal) return false;

    const form = modal.querySelector('form');
    const reason = form.querySelector('[name="reason"]').value.trim();

    const fields = Array.from(form.querySelectorAll('input[name="requested_fields[]"]:checked'))
        .map(el => el.value);

    if (fields.length === 0) {
        alert('Please select at least one field to change.');
        return false;
    }
    if (!reason) {
        alert('Please provide a reason for the change.');
        return false;
    }

    // Preview payload
    console.log('Preview SI Change Request', {
        booking_id: bookingId,
        shipping_instruction_id: siId,
        requested_fields: fields,
        reason: reason
    });

    alert(
        'Preview only (no save yet):\n' +
        JSON.stringify({ booking_id: bookingId, si_id: siId, requested_fields: fields, reason }, null, 2)
    );

    // keep modal open for now; return false to prevent submit
    return false;
}

function openSiApproveModal(id){ document.getElementById(`si-approve-modal-${id}`)?.classList.remove('hidden'); }
function closeSiApproveModal(id){ document.getElementById(`si-approve-modal-${id}`)?.classList.add('hidden'); }
function openSiCancelModal(id){ document.getElementById(`si-cancel-modal-${id}`)?.classList.remove('hidden'); }
function closeSiCancelModal(id){ document.getElementById(`si-cancel-modal-${id}`)?.classList.add('hidden'); }
function openSiFinalModal(id){ document.getElementById(`si-final-modal-${id}`)?.classList.remove('hidden'); }
function closeSiFinalModal(id){ document.getElementById(`si-final-modal-${id}`)?.classList.add('hidden'); }

// Timeline modal functions
function openSiTimelineModal(siId) {
    const modal = document.getElementById(`si-timeline-modal-${siId}`);
    if (!modal) return;
    
    modal.classList.remove('hidden');
    loadSiTimeline(siId);
}

function closeSiTimelineModal(siId) {
    document.getElementById(`si-timeline-modal-${siId}`)?.classList.add('hidden');
}

function loadSiTimeline(siId) {
    const contentDiv = document.getElementById(`si-timeline-content-${siId}`);
    if (!contentDiv) return;

    // Get all change request IDs for this SI from data attribute
    const siElement = document.querySelector(`[data-si-id="${siId}"]`);
    if (!siElement) {
        contentDiv.innerHTML = '<p class="text-center text-gray-500 py-8">No change requests found.</p>';
        return;
    }

    const requestIds = JSON.parse(siElement.getAttribute('data-request-ids') || '[]');
    
    if (requestIds.length === 0) {
        contentDiv.innerHTML = '<p class="text-center text-gray-500 py-8">No change requests found.</p>';
        return;
    }

    // Load timelines for all requests
    Promise.all(requestIds.map(id => 
        fetch(`/si-change-requests/${id}/timeline`)
            .then(res => res.json())
            .catch(() => null)
    )).then(results => {
        renderTimelines(contentDiv, results.filter(r => r !== null));
    });
}

function renderTimelines(container, timelines) {
    if (timelines.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="mt-3 text-sm font-medium text-gray-600">No timeline data available.</p>
            </div>
        `;
        return;
    }

    let html = '';
    
    timelines.forEach((data, index) => {
        const { timeline, request } = data;
        
        if (index > 0) {
            html += '<div class="border-t border-gray-200 my-4"></div>';
        }

        // Format submission date
        let submissionDate = '';
        if (request.created_at) {
            try {
                const date = typeof request.created_at === 'string' ? new Date(request.created_at) : new Date(request.created_at.date || request.created_at);
                if (!isNaN(date.getTime())) {
                    submissionDate = date.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                }
            } catch (e) {
                submissionDate = '';
            }
        }

        html += `
            <div class="mb-4 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-50 to-indigo-100/50 px-4 py-2.5 border-b border-indigo-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="flex-shrink-0">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-gray-900">
                                    Change Request${submissionDate ? ` • ${submissionDate}` : ''}
                                </h4>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold ${getStatusBadgeClasses(request.status)}">
                            ${getStatusIcon(request.status)}
                            ${getStatusLabel(request.status)}
                        </span>
                    </div>
                </div>
                <div class="px-4 py-3">
                    <div class="relative">
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gradient-to-b from-indigo-200 via-indigo-300 to-indigo-200"></div>
                        <div class="space-y-3">
        `;

        timeline.forEach((event, eventIndex) => {
            const isLast = eventIndex === timeline.length - 1;
            const eventType = getEventType(event.label);
            let timeStr = 'N/A';
            if (event.at) {
                try {
                    // Handle both string and object formats from Laravel
                    const date = typeof event.at === 'string' ? new Date(event.at) : new Date(event.at.date || event.at);
                    if (!isNaN(date.getTime())) {
                        timeStr = date.toLocaleString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }
                } catch (e) {
                    timeStr = typeof event.at === 'string' ? event.at : 'N/A';
                }
            }

            html += `
                <div class="relative flex gap-3 pl-1">
                    <div class="relative z-10 flex-shrink-0 flex h-7 w-7 items-center justify-center rounded-full ${eventType.bg} ring-2 ring-white shadow-sm">
                        ${isLast ? eventType.iconActive : eventType.icon}
                    </div>
                    <div class="flex-1 pb-3 min-w-0">
                        <div class="bg-white rounded-md border border-gray-200 p-2.5 hover:shadow-sm transition-shadow">
                            <div class="flex items-start justify-between gap-2 mb-1.5">
                                <div class="flex-1 min-w-0">
                                    <h5 class="text-xs font-semibold ${eventType.textColor} mb-0.5">
                                        ${escapeHtml(event.label)}
                                    </h5>
                                    <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                        <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="truncate">${timeStr}</span>
                                        ${event.by ? `
                                            <span class="text-gray-400">•</span>
                                            <span class="font-medium truncate">${escapeHtml(event.by)}</span>
                                        ` : ''}
                                    </div>
                                </div>
                            </div>
                            ${event.note ? `
                                <div class="mt-2 pt-2 border-t border-gray-100">
                                    <div class="flex items-start gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-medium text-gray-500 mb-0.5">Note</p>
                                            <p class="text-xs text-gray-700 leading-snug">${escapeHtml(event.note)}</p>
                                        </div>
                                    </div>
                                </div>
                            ` : ''}
                            ${event.meta && event.meta.approved_fields && event.meta.approved_fields.length > 0 ? `
                                <div class="mt-2 pt-2 border-t border-gray-100">
                                    <div class="flex items-start gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-medium text-gray-500 mb-1.5">Approved Fields</p>
                                            <div class="flex flex-wrap gap-1">
                                                ${event.meta.approved_fields.map(f => `
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                                        ${escapeHtml(f.replace(/_/g, ' '))}
                                                    </span>
                                                `).join('')}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ` : ''}
                            ${event.meta && event.meta.draft_changes_keys && event.meta.draft_changes_keys.length > 0 ? `
                                <div class="mt-2 pt-2 border-t border-gray-100">
                                    <div class="flex items-start gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-medium text-gray-500 mb-1.5">Draft Changes</p>
                                            <div class="flex flex-wrap gap-1">
                                                ${event.meta.draft_changes_keys.map(f => `
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                                        ${escapeHtml(f.replace(/_/g, ' '))}
                                                    </span>
                                                `).join('')}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        });

        html += `
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}

function getEventType(label) {
    const lowerLabel = label.toLowerCase();
    if (lowerLabel.includes('submitted') || lowerLabel.includes('created')) {
        return {
            bg: 'bg-blue-100',
            textColor: 'text-blue-900',
            icon: '<div class="h-2 w-2 rounded-full bg-blue-600"></div>',
            iconActive: '<svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>'
        };
    } else if (lowerLabel.includes('approved')) {
        return {
            bg: 'bg-green-100',
            textColor: 'text-green-900',
            icon: '<div class="h-2 w-2 rounded-full bg-green-600"></div>',
            iconActive: '<svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>'
        };
    } else if (lowerLabel.includes('rejected')) {
        return {
            bg: 'bg-red-100',
            textColor: 'text-red-900',
            icon: '<div class="h-2 w-2 rounded-full bg-red-600"></div>',
            iconActive: '<svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>'
        };
    } else if (lowerLabel.includes('edit') || lowerLabel.includes('updated')) {
        return {
            bg: 'bg-indigo-100',
            textColor: 'text-indigo-900',
            icon: '<div class="h-2 w-2 rounded-full bg-indigo-600"></div>',
            iconActive: '<svg class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>'
        };
    } else if (lowerLabel.includes('expired') || lowerLabel.includes('cancelled')) {
        return {
            bg: 'bg-gray-100',
            textColor: 'text-gray-900',
            icon: '<div class="h-2 w-2 rounded-full bg-gray-600"></div>',
            iconActive: '<svg class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>'
        };
    } else {
        return {
            bg: 'bg-indigo-100',
            textColor: 'text-indigo-900',
            icon: '<div class="h-2 w-2 rounded-full bg-indigo-600"></div>',
            iconActive: '<svg class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        };
    }
}

function getStatusIcon(status) {
    const icons = {
        'under_review': '<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        'approved_for_edit': '<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        'pending_final_review': '<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>',
        'approved_applied': '<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>',
        'rejected': '<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>',
        'cancelled': '<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>',
        'expired': '<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
    };
    return icons[status] || '<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
}

function getStatusLabel(status) {
    const labels = {
        'under_review': 'Under Review',
        'approved_for_edit': 'Approved for Edit',
        'pending_final_review': 'Pending Final Review',
        'approved_applied': 'Approved & Applied',
        'rejected': 'Rejected',
        'cancelled': 'Cancelled',
        'expired': 'Expired'
    };
    return labels[status] || status;
}

function getStatusBadgeClasses(status) {
    const classes = {
        'under_review': 'bg-amber-100 text-amber-800',
        'approved_for_edit': 'bg-blue-100 text-blue-800',
        'pending_final_review': 'bg-indigo-100 text-indigo-800',
        'approved_applied': 'bg-green-100 text-green-800',
        'rejected': 'bg-red-100 text-red-800',
        'cancelled': 'bg-gray-100 text-gray-800',
        'expired': 'bg-gray-100 text-gray-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Show rejected request modal on page load if there's a rejected request
@if($rejectedRequest)
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('rejected-request-modal');
    if (modal) {
        modal.classList.remove('hidden');
    }
});

function closeRejectedRequestModal() {
    const modal = document.getElementById('rejected-request-modal');
    if (modal) {
        modal.classList.add('hidden');
    }
}
@endif

</script>

