<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <h4 class="sr-only">Status</h4>
                        <!-- This should be the current status of the booking -->
                        <p class="text-sm font-medium text-gray-900">Generating Bill of Lading ...</p>
                        <div class="mt-6" aria-hidden="true">
                            <div class="overflow-hidden rounded-full bg-gray-200">
                                <div class="h-2 rounded-full bg-indigo-600" style="width: 50%"></div>
                            </div>
                            <div class="mt-6 hidden grid-cols-6 text-sm font-medium text-gray-600 sm:grid">
                                <div class="text-indigo-600">Booking<br>Created</div>
                                <div class="text-center text-indigo-600">Update<br>SI</div>
                                <div class="text-center text-indigo-600">Upload<br>Invoice</div>
                                <div class="text-center text-indigo-600">Complete<br>Payment</div>
                                <div class="text-center text-indigo-600">Generate<br>BL</div>
                                <div class="text-right text-indigo-600">Sailing</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            @if ($booking->status == 'Pending SI')
                @if(auth()->user()->role == 'customer')
                <x-alert-instruction 
                    message="Please submit your shipping instructions"
                />
                @else
                <x-alert-instruction 
                    message="A booking is pending for shipping instructions"
                />
                @endif
            @endif

            @if ($booking->status == 'Pending Invoice')
                @if(auth()->user()->role == 'customer')
                <x-alert-instruction 
                    message="Waiting for Liner to upload the invoice"
                />
                @else
                <x-alert-instruction 
                    message="Customer has submitted the shipping instructions, please upload the invoice to proceed"
                    action_url="#"
                    action_text="Upload Invoice"
                />
                @endif
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <x-alert-success :message="session('success')" />
            @endif

            <!-- Booking Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">
                    <!-- Header with Booking Number and Status -->

                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <div class="flex items-center gap-3">
                                <h2 class="text-2xl font-semibold">Booking Details</h2>
                                <span
                                    class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">{{ $booking->status }}</span>
                            </div>
                            <p class="text-gray-600">{{ $booking->booking_number }}</p>
                        </div>
                        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                            <button type="button"
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
                            <button type="button"
                                class="inline-flex items-center gap-x-1.5 rounded-md bg-red-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 uppercase tracking-widest">
                                <svg class="-ml-0.5 size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                                    data-slot="icon">
                                    <path fill-rule="evenodd"
                                        d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                        clip-rule="evenodd" />
                                </svg>
                                Cancel
                            </button>
                        </div>
                    </div>

                    <!-- Service Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium mb-4">Service Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Service Type</p>
                                <p class="font-medium">{{ $booking->service }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Liner</p>
                                <p class="font-medium">{{ $booking->liner_address }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Vessel Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium mb-4">Vessel Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Vessel Name</p>
                                <p class="font-medium">{{ $booking->vessel }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Voyage Number</p>
                                <p class="font-medium">{{ $booking->voyage }}</p>
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
                                <p class="font-medium">{{ $booking->ets->format('Y-m-d H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Estimated Time of Arrival</p>
                                <p class="font-medium">{{ $booking->eta->format('Y-m-d H:i') }}</p>
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
                            @if($totalUnallocated > 0)
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
                                            <a href="{{ route('shipping-instructions.show', $si) }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                View Details
                                            </a>
                                            @if($booking->status == 'Complete Payment' || $booking->status == 'Shipped' || $booking->status == 'Completed')
                                            <a href="#"
                                                class="ml-4 text-green-600 hover:text-green-900">
                                                Generate BL
                                            </a>
                                            <a href="#"
                                                class="ml-4 text-green-600 hover:text-green-900">
                                                Generate Manifest
                                            </a>
                                            @endif
                                            <form action="{{ route('shipping-instructions.destroy', $si) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="ml-4 text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Are you sure you want to delete this shipping instruction?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Only viewable by admin -->
                    <!-- Invoice Information -->
                    @if($booking->status == 'Pending Invoice' || $booking->status == 'Pending Payment' || $booking->status == 'Complete Payment' || $booking->status == 'Shipped' || $booking->status == 'Completed')
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="sm:flex sm:items-center mb-4">
                            <div class="sm:flex-auto">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-medium">Invoice Information</h3>
                                    <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">Pending</span>
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/10">Uploaded</span>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Actions -->
                        <div class="mb-6">
                            @if(auth()->user()->role == 'customer')
                                <button type="button" class="inline-flex items-center gap-x-1.5 rounded-md bg-blue-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 uppercase tracking-widest">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                    Download Invoice
                                </button>
                            @else
                                <div class="flex items-center gap-4">
                                    <form action="{{ route('invoice.upload', $booking) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-4">
                                        @csrf
                                        <div class="flex-grow max-w-md">
                                            <div class="flex items-center gap-2">
                                                <div class="relative flex-grow">
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
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button"
                                            onclick="extractInvoiceData()"
                                            class="inline-flex items-center gap-x-1.5 rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 uppercase tracking-widest">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                            Extract & Upload
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <!-- Invoice Details -->
                        <div class="grid grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="invoice_date" :value="__('Date of Invoice')" />
                                <x-text-input id="invoice_date" class="block mt-1 w-full" type="date" name="invoice_date" :value="$booking->invoice_date" />
                            </div>
                            <div>
                                <x-input-label for="invoice_number" :value="__('Invoice Number')" />
                                <x-text-input id="invoice_number" class="block mt-1 w-full" type="text" name="invoice_number" :value="$booking->invoice_number" />
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
                                        :value="$booking->total_amount" 
                                        placeholder="0.00"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- Payment Information -->
                    <!-- This button upload if no invoice. view if invoice is uploaded -->
                    <!-- Admin only can view the upload button invoice -->
                    <!-- hide if user session and no invoice uploaded? -->
                    @if($booking->status == 'Pending Payment' || $booking->status == 'Complete Payment' || $booking->status == 'Shipped' || $booking->status == 'Completed')
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="sm:flex sm:items-center mb-4">
                            <div class="sm:flex-auto">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-medium">Payment Information</h3>
                                    <span
                                        class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">Pending</span>
                                    <span
                                        class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/10">Completed</span>
                                </div>
                            </div>
                            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                                <button type="button"
                                    onclick="document.getElementById('upload-payment-slip-modal').classList.remove('hidden')"
                                    class="inline-flex items-center gap-x-1.5 rounded-md bg-blue-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 uppercase tracking-widest">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                    </svg>
                                    Upload/View Payment Slip
                                    <!-- Status change based on payment status -->
                                </button>
                            </div>
                        </div>

                        <!-- Upload Payment Slip Modal -->
                        <div id="upload-payment-slip-modal" class="hidden relative z-10" aria-labelledby="modal-title"
                            role="dialog" aria-modal="true">
                            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true">
                            </div>
                            <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
                                <div
                                    class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                    <div
                                        class="z-50 relative w-full transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-5xl sm:p-6">
                                        <div>
                                            <div class="mt-3 sm:mt-5">
                                                <div class="mt-4">
                                                    <div class="px-4 sm:px-6 lg:px-8">
                                                        <div class="sm:flex sm:items-center">
                                                            <div class="sm:flex-auto">
                                                                <h3 class="text-lg font-semibold text-gray-900"
                                                                    id="modal-title">
                                                                    Upload Payment Slip</h3>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-3">
                                                            <div>
                                                                <label for="payment-date"
                                                                    class="block text-sm/6 font-medium text-gray-900">Payment
                                                                    Date</label>
                                                                <div class="mt-2">
                                                                    <input type="date" name="payment-date"
                                                                        id="payment-date"
                                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <label for="payment-amount"
                                                                    class="block text-sm/6 font-medium text-gray-900">Payment
                                                                    Amount</label>
                                                                <div class="mt-2">
                                                                    <input type="number" name="payment-amount"
                                                                        id="payment-amount" step="0.01"
                                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                                        placeholder="0.00">
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <label for="payment-method"
                                                                    class="block text-sm/6 font-medium text-gray-900">Payment
                                                                    Method</label>
                                                                <div class="mt-2">
                                                                    <select id="payment-method" name="payment-method"
                                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                                        <option>Bank Transfer</option>
                                                                        <option>Credit Card</option>
                                                                        <option>Debit Card</option>
                                                                        <option>Cash</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-8 flow-root">
                                                            <!-- File Upload -->
                                                            <div>
                                                                <label
                                                                    class="block text-sm font-medium text-gray-700">Upload
                                                                    Payment Slip</label>
                                                                <div
                                                                    class="mt-1 flex items-center justify-center w-full">
                                                                    <label
                                                                        class="w-full flex flex-col items-center px-4 py-6 bg-white rounded-lg border-2 border-dashed border-gray-300 cursor-pointer hover:border-indigo-600">
                                                                        <svg class="w-8 h-8 text-gray-500" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M12 4v16m8-8H4" />
                                                                        </svg>
                                                                        <span class="mt-2 text-sm text-gray-600">Click
                                                                            to upload or drag and
                                                                            drop (.jpeg, .png, .jpg)</span>
                                                                        <input type="file" class="hidden"
                                                                            accept=".jpeg,.png,.jpg">
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-5 sm:mt-6 flex space-x-3">
                                            <button type="button"
                                                onclick="document.getElementById('upload-payment-slip-modal').classList.add('hidden')"
                                                class="inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                                Cancel
                                            </button>
                                            <a href="#"
                                                class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                                Upload
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Payment Date</p>
                                <p class="font-medium">{{ $booking->place_of_receipt }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Payment Amount</p>
                                <p class="font-medium">{{ $booking->pol }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Payment Method</p>
                                <p class="font-medium">{{ $booking->pod }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Payment Status</p>
                                <p class="font-medium">Submitted</p>
                            </div>
                        </div>
                    </div>
                    @endif


                    <!-- Action Buttons -->
                    <div class="mt-6 flex justify-between space-x-4">
                        <div>
                            <button onclick="window.history.back()"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Back
                            </button>
                        </div>
                        <div class="flex space-x-4">
                            @if($booking->status == 'Pending SI')
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
                            @elseif($booking->status == 'Pending Invoice')
                            <div class="relative" x-data="{ showTooltip: false }">
                                <!-- Button with mouseover tooltip -->
                                <button type="button"
                                    @mouseover="showTooltip = true"
                                    @mouseleave="showTooltip = false"
                                    onclick="document.getElementById('invoice-submission-modal').classList.remove('hidden')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest 
                                        @if($booking->shippingInstructions->isNotEmpty() && $totalUnallocated === 0)
                                            bg-blue-600 text-white hover:bg-blue-700
                                        @else
                                            bg-gray-300 text-gray-500 cursor-not-allowed
                                        @endif"
                                    @if($booking->shippingInstructions->isEmpty() || $totalUnallocated > 0) disabled @endif>
                                    Submit Invoice
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
                            @endif
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
                                                        <p class="text-sm text-gray-500">Please confirm that all the information are correct before submitting the Shipping Instructions. You will not be able to make any changes after submitting.</p>
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
                                                <button type="button" onclick="window.location.href='{{ route('booking.submit-invoice', $booking) }}'"
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

//function uploadInvoice(formData) {
//    return fetch('{{ route("invoice.upload", $booking) }}', {
//        method: 'POST',
//        body: formData
//    })
//    .then(response => response.json())
//    .then(data => {
//        if (data.success) {
//            // Show success message
//            alert('Invoice uploaded successfully!');
//            // Optionally refresh the page or update UI
//            window.location.reload();
//        } else {
//            throw new Error(data.message || 'Failed to upload invoice');
//        }
//    });
//}
</script>