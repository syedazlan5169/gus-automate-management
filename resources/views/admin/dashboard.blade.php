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

                    <!-- More items... -->
                </div>

            </div>

            <h2 class="mx-auto mt-8 max-w-10xl px-4 text-lg/6 font-medium text-gray-900 sm:px-6 lg:px-8">Recent Bookings
            </h2>

            

            <!-- Activity table (small breakpoint and up) -->
            <div class="hidden sm:block">
                <div class="mx-auto max-w-10xl px-4 sm:px-6 lg:px-8">
                    <div class="mt-2 flex flex-col">
                        <div class="min-w-full overflow-hidden overflow-x-auto align-middle shadow sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col">Booking Number</th>
                                        <th class="bg-gray-50 px-6 py-3 text-center text-sm font-semibold text-gray-900"
                                            scope="col">Amount</th>
                                        <th class="hidden bg-gray-50 px-6 py-3 text-center text-sm font-semibold text-gray-900 md:block"
                                            scope="col">Status</th>
                                        <th class="bg-gray-50 px-6 py-3 text-center text-sm font-semibold text-gray-900"
                                            scope="col">Sailing Date</th>
                                        <th class="bg-gray-50 px-6 py-3 text-center text-sm font-semibold text-gray-900"
                                            scope="col">Arrival Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr class="bg-white align-middle">
                                        <td class="w-full max-w-0 whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                            <div class="flex">
                                                <a href="#" class="group inline-flex space-x-2 truncate text-sm">
                                                    <div class="flex flex-col">
                                                        <p class="truncate font-bold text-gray-900">BC00001203030</p>
                                                        <p class="truncate text-xs text-gray-500">Created on 2025-03-01
                                                        </p>
                                                    </div>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                            <span class="font-medium text-gray-900">$20,000</span>
                                            USD
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                            <p
                                                class="mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                Complete</p>

                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                            <time datetime="2020-07-11">July 11, 2020</time>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                            <time datetime="2020-07-11">July 11, 2020</time>
                                        </td>
                                    </tr>
                                    <tr class="bg-white align-middle">
                                        <td class="w-full max-w-0 whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                            <div class="flex">
                                                <a href="#" class="group inline-flex space-x-2 truncate text-sm">
                                                    <div class="flex flex-col">
                                                        <p class="truncate font-bold text-gray-900">BC00001203030</p>
                                                        <p class="truncate text-xs text-gray-500">Created on 2025-03-01
                                                        </p>
                                                    </div>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                            <span class="font-medium text-gray-900">$20,000</span>
                                            USD
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                            <p
                                                class="mt-0.5 whitespace-nowrap rounded-md bg-blue-50 px-1.5 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">
                                                Processing</p>

                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                            <time datetime="2020-07-11">July 11, 2020</time>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                            <time datetime="2020-07-11">July 11, 2020</time>
                                        </td>
                                    </tr>
                                    <tr class="bg-white align-middle">
                                        <td class="w-full max-w-0 whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                            <div class="flex">
                                                <a href="#" class="group inline-flex space-x-2 truncate text-sm">
                                                    <div class="flex flex-col">
                                                        <p class="truncate font-bold text-gray-900">BC00001203030</p>
                                                        <p class="truncate text-xs text-gray-500">Created on 2025-03-01
                                                        </p>
                                                    </div>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                            <span class="font-medium text-gray-900">$20,000</span>
                                            USD
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                            <p
                                                class="mt-0.5 whitespace-nowrap rounded-md bg-yellow-50 px-1.5 py-0.5 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-600/20">
                                                Pending</p>

                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                            <time datetime="2020-07-11">July 11, 2020</time>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                            <time datetime="2020-07-11">July 11, 2020</time>
                                        </td>
                                    </tr>

                                    <!-- More transactions... -->
                                </tbody>
                            </table>
                            <!-- Pagination -->
                            <nav class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6"
                                aria-label="Pagination">
                                <div class="hidden sm:block">
                                    <p class="text-sm text-gray-700">
                                        Showing
                                        <span class="font-medium">1</span>
                                        to
                                        <span class="font-medium">10</span>
                                        of
                                        <span class="font-medium">20</span>
                                        results
                                    </p>
                                </div>
                                <div class="flex flex-1 justify-between gap-x-3 sm:justify-end">
                                    <a href="#"
                                        class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:ring-gray-400">Previous</a>
                                    <a href="#"
                                        class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:ring-gray-400">Next</a>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


</x-app-layout>
