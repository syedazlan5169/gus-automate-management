<x-app-layout>

    <main class="flex-1 pb-8">
        <!-- Page header -->
        <div class="bg-white shadow">
            <div class="px-4 sm:px-6 lg:mx-auto lg:max-w-10xl lg:px-8">
                <div class="py-6 md:flex md:items-center md:justify-between lg:border-t lg:border-gray-200">
                    <div class="min-w-0 flex-1">
                        <!-- Profile -->
                        <div class="flex items-center">
                            <div>
                                <div class="flex items-center">

                                    <h1 class="ml-3 text-2xl/7 font-bold text-gray-900 sm:truncate sm:text-2xl/9">Hello,
                                        {{ Auth::user()->name }}
                                    </h1>
                                </div>
                                <dl class="mt-6 flex flex-col sm:ml-3 sm:mt-1 sm:flex-row sm:flex-wrap">
                                    <dt class="sr-only">Company</dt>
                                    <dd class="flex items-center text-sm font-medium capitalize text-gray-500 sm:mr-6">
                                        <svg class="mr-1.5 size-5 shrink-0 text-gray-400" viewBox="0 0 20 20"
                                            fill="currentColor" aria-hidden="true" data-slot="icon">
                                            <path fill-rule="evenodd"
                                                d="M4 16.5v-13h-.25a.75.75 0 0 1 0-1.5h12.5a.75.75 0 0 1 0 1.5H16v13h.25a.75.75 0 0 1 0 1.5h-3.5a.75.75 0 0 1-.75-.75v-2.5a.75.75 0 0 0-.75-.75h-2.5a.75.75 0 0 0-.75.75v2.5a.75.75 0 0 1-.75.75h-3.5a.75.75 0 0 1 0-1.5H4Zm3-11a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 9a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM11 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm.5 3.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ Auth::user()->company_name }}
                                    </dd>
                                    <dt class="sr-only">Account status</dt>
                                    <dd
                                        class="mt-3 flex items-center text-sm font-medium capitalize text-gray-500 sm:mr-6 sm:mt-0">
                                        @if(Auth::user()->email_verified_at)
                                            <svg class="mr-1.5 size-5 shrink-0 text-green-400" viewBox="0 0 20 20"
                                                fill="currentColor" aria-hidden="true" data-slot="icon">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Verified account
                                        @else
                                            <svg class="mr-1.5 size-5 shrink-0 text-yellow-400" viewBox="0 0 20 20"
                                                fill="currentColor" aria-hidden="true" data-slot="icon">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm1-12a1 1 0 1 0-2 0v4a1 1 0 0 0 2 0V6Zm-1 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Unverified account
                                        @endif
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <div class="mx-auto max-w-10xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-lg/6 font-medium text-gray-900">Overview</h2>
                <div class="mt-2 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Card -->
                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <svg class="size-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" aria-hidden="true" data-slot="icon">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="truncate text-sm font-medium text-green-500">Completed Bookings</dt>
                                        <dd>
                                            <div class="text-lg font-medium text-gray-900">{{ $completedBookings }}</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-5 py-3">
                            <div class="text-sm">
                                <a href="{{ route('bookings.index', ['status' => \App\Models\BookingStatus::COMPLETED]) }}" class="font-medium text-cyan-700 hover:text-cyan-900">View all</a>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <svg class="size-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" aria-hidden="true" data-slot="icon">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="truncate text-sm font-medium text-blue-500">Ongoing Bookings</dt>
                                        <dd>
                                            <div class="text-lg font-medium text-gray-900">{{ $ongoingBookings }}</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-5 py-3">
                            <div class="text-sm">
                                <a href="{{ route('bookings.index', ['status' => 'ongoing']) }}" class="font-medium text-cyan-700 hover:text-cyan-900">View all</a>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <svg class="size-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" aria-hidden="true" data-slot="icon">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="truncate text-sm font-medium text-red-500">Cancelled Bookings</dt>
                                        <dd>
                                            <div class="text-lg font-medium text-gray-900">{{ $cancelledBookings }}</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-5 py-3">
                            <div class="text-sm">
                                <a href="{{ route('bookings.index', ['status' => \App\Models\BookingStatus::CANCELLED]) }}" class="font-medium text-cyan-700 hover:text-cyan-900">View all</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Activity table -->
                <div class="w-full lg:w-1/2 mt-8">
                    <div class="sm:block">
                        <div class="px-4 sm:px-6 lg:px-8">
                            <h2 class="text-lg/6 font-medium text-gray-900">Recent Activities</h2>
                            <div class="mt-2 flex flex-col">
                                <div class="min-w-full overflow-hidden overflow-x-auto align-middle shadow sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200 border rounded-lg border-gray-300">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-90">Action</th>
                                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-90">Description</th>
                                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-90">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($recentActivities as $activity)
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-2 text-sm text-gray-900 font-semibold">
                                                        {{ $activity->action }}
                                                    </td>
                                                    <td class="px-6 py-2 text-xs text-gray-600 italic">
                                                        {{ $activity->description }}
                                                    </td>
                                                    <td class="px-6 py-2 text-sm text-gray-500">
                                                        {{ $activity->created_at->format('d/m/Y H:i') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="px-4 py-3">
                                        {{ $recentActivities->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Booking Chart -->
                <div class="w-full lg:w-1/2 mt-8">
                    <div class="sm:block">
                        <div class="px-4 sm:px-6 lg:px-8">
                            <h2 class="text-lg/6 font-medium text-gray-900">Booking Trends</h2>
                            <div class="mt-2 flex flex-col">
                                <div class="min-w-full overflow-hidden overflow-x-auto align-middle shadow sm:rounded-lg">
                                    <div class="p-4 border rounded-lg border-gray-300" style="height: 475px;">
                                        <canvas id="monthlyBookingsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    @push('scripts')
    <script>
        window.monthlyData = @json(array_values($monthlyData));
    </script>
    @vite(['resources/js/dashboard.js'])
    @endpush

</x-app-layout>
