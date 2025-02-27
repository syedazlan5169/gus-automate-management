<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header with Booking Number and Status -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold">Booking Details</h2>
                            <p class="text-gray-600">Booking Number: {{ $booking->booking_number }}</p>
                        </div>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full 
                            {{ match($booking->status) {
                                'New' => 'bg-gray-100 text-gray-800',
                                'Pending' => 'bg-yellow-100 text-yellow-800',
                                'Confirmed' => 'bg-green-100 text-green-800',
                                'Shipped' => 'bg-blue-100 text-blue-800',
                                'Completed' => 'bg-indigo-100 text-indigo-800',
                                'Cancelled' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800'
                            } }}">
                            {{ $booking->status }}
                        </span>
                    </div>

                    <!-- Service Information -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
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
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
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
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
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
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
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

                    <!-- Cargo Information -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium">Cargo Information</h3>
                            @if($booking->status === 'New')
                                <a href="{{ route('shipping-instructions.create', $booking) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                    Add Shipping Instruction
                                </a>
                            @endif
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Container Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Count</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Weight</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($booking->cargos as $cargo)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $cargo->container_type }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $cargo->container_count }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($cargo->total_weight, 2) }} kg</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if($cargo->shipping_instruction_id)
                                                    <span class="text-green-600">Allocated</span>
                                                @else
                                                    <span class="text-yellow-600">Pending Allocation</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Shipping Instructions -->
                    @if($booking->shippingInstructions->isNotEmpty())
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Shipping Instructions</h3>
                            <div class="space-y-4">
                                @foreach($booking->shippingInstructions as $si)
                                    <div class="border rounded p-4 bg-white">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-sm text-gray-600">Shipper</p>
                                                <p class="font-medium">{{ $si->shipper }}</p>
                                                <p class="text-sm text-gray-500">{{ $si->contact_shipper }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600">Consignee</p>
                                                <p class="font-medium">{{ $si->consignee }}</p>
                                                <p class="text-sm text-gray-500">{{ $si->contact_consignee }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <a href="{{ route('shipping-instructions.show', $si) }}" 
                                               class="text-indigo-600 hover:text-indigo-900">
                                                View Details
                                            </a>
                                            <a href="{{ route('shipping-instructions.bl', $si) }}" 
                                               class="ml-4 text-green-600 hover:text-green-900">
                                                Generate BL
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="mt-6 flex justify-end space-x-4">
                        @if(auth()->user()->role !== 'customer')
                            <a href="{{ route('booking.update', $booking) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Edit
                            </a>
                        @endif
                        <button onclick="window.history.back()" 
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Back
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
