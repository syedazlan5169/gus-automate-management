<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">My Bookings</h2>
                        <a href="{{ route('booking.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            New Booking
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Booking Number
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Service
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Vessel / Voyage
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Route
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Schedule
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($bookings as $booking)
                                                                <tr>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                        {{ $booking->booking_number }}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                        {{ $booking->service }}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                        {{ $booking->vessel }} / {{ $booking->voyage }}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                        {{ $booking->pol }} → {{ $booking->pod }}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                        <div>ETS: {{ $booking->ets->format('Y-m-d H:i') }}</div>
                                                                        <div>ETA: {{ $booking->eta->format('Y-m-d H:i') }}</div>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                                                                {{ match ($booking->status) {
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
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                        <div class="flex items-center justify-center">
                                                                            <a href="{{ route('booking.show', $booking) }}"
                                                                                class="text-indigo-600 hover:text-indigo-900">
                                                                                View
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="7"
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            No bookings found. <a href="{{ route('booking.create') }}"
                                                class="text-indigo-600 hover:text-indigo-900">Create your first booking</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($bookings, 'links'))
                        <div class="mt-4">
                            {{ $bookings->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>