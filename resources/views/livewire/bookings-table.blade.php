<div>
<div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
    <div class="w-full">
        <!-- Search and Per Page Controls -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 space-y-2 sm:space-y-0">
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                <div class="w-full sm:w-64">
                    <input wire:model.live="search" type="text" placeholder="Search bookings..." 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div class="w-full sm:w-48">
                    <select wire:model.live="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">All Status</option>
                        @foreach(\App\Models\BookingStatus::getAllStatuses() as $statusValue)
                            <option value="{{ $statusValue }}">{{ \App\Models\BookingStatus::labels($statusValue)[$statusValue] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('booking.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create Booking
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="mx-4 mt-3 ring-1 ring-gray-300 sm:mx-0 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
                  <thead>
                    <tr>
                        <th scope="col" class="text-center hidden px-3 py-3.5 text-sm font-semibold text-gray-900 lg:table-cell">
                            Booking Number
                        </th>
                        <th scope="col" class="text-center hidden px-3 py-3.5 text-sm font-semibold text-gray-900 lg:table-cell">
                            Vessel | Voyage
                        </th>
                        <th scope="col" class="text-center hidden px-3 py-3.5 text-sm font-semibold text-gray-900 lg:table-cell">
                            Route
                        </th>
                        <th scope="col" class="text-center hidden px-3 py-3.5 text-sm font-semibold text-gray-900 lg:table-cell">
                            Invoice
                        </th>
                        <th scope="col" class="text-center px-3 py-3.5 text-sm font-semibold text-gray-900">
                            Status
                        </th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                            <span class="sr-only">Action</span>
                        </th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                    <tr>
                      <td class="relative py-4 pl-4 pr-3 text-sm sm:pl-6">
                        <div>
                            <div class="font-medium text-gray-900">{{ $booking->booking_number }}</div>
                            <div class="text-gray-500">
                                <span>{{ $booking->user->company_name ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <!-- Mobile View -->
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                            <span>ETS : {{ $booking->booking_date ? $booking->booking_date->format('d-m-Y | g:i A') : 'Not set' }}</span>
                        </div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                            <span>{{ $booking->vessel }} | {{ $booking->voyage->voyage_number }}</span>
                          <span class="hidden sm:inline">·</span>
                            <span>{{ $booking->pol }} → {{ $booking->pod }}</span>
                        </div>
                        </td>

                        <!-- Desktop View -->
                        <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">{{ $booking->vessel }} | {{ $booking->voyage->voyage_number }}</td>
                        <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">{{ $booking->pol }} → {{ $booking->pod }}</td>
                        <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">
                            @php
                                $invoiceStatus = $this->getInvoiceStatusLabel($booking);
                                $invoiceStatusClass = 'bg-gray-50 text-gray-700 ring-gray-600/20';
                                
                                if ($invoiceStatus === 'Paid') {
                                    $invoiceStatusClass = 'bg-green-50 text-green-700 ring-green-600/20';
                                } elseif ($invoiceStatus === 'Unpaid') {
                                    $invoiceStatusClass = 'bg-yellow-50 text-yellow-700 ring-yellow-600/20';
                                } elseif ($invoiceStatus === 'Overdue') {
                                    $invoiceStatusClass = 'bg-red-50 text-red-700 ring-red-600/20';
                                } elseif ($invoiceStatus === 'No Invoice') {
                                    $invoiceStatusClass = 'bg-gray-50 text-gray-500 ring-gray-600/20';
                                }
                            @endphp
                            <div class="whitespace-nowrap rounded-md {{ $invoiceStatusClass }} px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset">
                                {{ $invoiceStatus }}
                            </div>
                        </td>

                        <td class="text-center px-3 py-3.5 text-sm text-gray-500">
                            @php
                                $statusClass = 'bg-green-50 text-green-700 ring-green-600/20';
                                if ($booking->status == \App\Models\BookingStatus::CANCELLED) {
                                    $statusClass = 'bg-red-50 text-red-700 ring-red-600/20';
                                } elseif ($booking->status == \App\Models\BookingStatus::NEW) {
                                    $statusClass = 'bg-blue-50 text-blue-700 ring-blue-600/20';
                                } elseif ($booking->status == \App\Models\BookingStatus::BOOKING_CONFIRMED) {
                                    $statusClass = 'bg-yellow-50 text-yellow-700 ring-yellow-600/20';
                                }
                            @endphp
                            <div class="sm:hidden mt-0.5 whitespace-nowrap rounded-md {{ $statusClass }} px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset">
                                {{ $statusLabels[$booking->id] }}
                            </div>
                            <div class="hidden sm:block mt-0.5 whitespace-nowrap rounded-md {{ $statusClass }} px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset">
                                {{ $statusLabels[$booking->id] }}
                            </div>
                        </td>
                        <td class="relative py-3.5 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="{{ route('booking.show', $booking) }}" 
                                class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white">
                                    View
                                </a>
                        </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex flex-col sm:flex-row justify-between items-center">
            <div class="flex items-center mb-4 sm:mb-0">
                <span class="text-sm text-gray-700 mr-2">Show:</span>
                <select wire:model.live="perPage" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-sm text-gray-700 ml-2">entries</span>
            </div>
            <div>
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@foreach($bookings as $booking)
    <x-modal name="confirm-route-deletion-{{ $booking->id }}" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="delete({{ $booking->id }})" class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete this booking?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once this booking is deleted, all of its resources and data will be permanently deleted.') }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ml-3">
                    {{ __('Delete Booking') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
@endforeach
</div>