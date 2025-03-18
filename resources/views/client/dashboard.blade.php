<x-app-layout>

    <!-- <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("View from Client Dashboard Page") }}
                </div>
            </div>
        </div>
    </div> -->
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
                                        Company Name
                                    </dd>
                                    <dt class="sr-only">Account status</dt>
                                    <dd
                                        class="mt-3 flex items-center text-sm font-medium capitalize text-gray-500 sm:mr-6 sm:mt-0">
                                        <svg class="mr-1.5 size-5 shrink-0 text-green-400" viewBox="0 0 20 20"
                                            fill="currentColor" aria-hidden="true" data-slot="icon">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Verified account
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="mt-6 flex space-x-3 md:ml-4 md:mt-0">
                        <button type="button"
                            class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Add
                            money</button>
                        <button type="button"
                            class="inline-flex items-center rounded-md bg-cyan-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-cyan-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cyan-600">Send
                            money</button>
                    </div> -->
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
                                            d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 0 1-2.031.352 5.988 5.988 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971Zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 0 1-2.031.352 5.989 5.989 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971Z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="truncate text-sm font-medium text-gray-500">Pending Actions</dt>
                                        <dd>
                                            <div class="text-lg font-medium text-gray-900">12</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-5 py-3">
                            <div class="text-sm">
                                <a href="#" class="font-medium text-cyan-700 hover:text-cyan-900">View all</a>
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
                                            d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 0 1-2.031.352 5.988 5.988 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971Zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 0 1-2.031.352 5.989 5.989 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971Z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="truncate text-sm font-medium text-gray-500">Ongoing Bookings</dt>
                                        <dd>
                                            <div class="text-lg font-medium text-gray-900">33</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-5 py-3">
                            <div class="text-sm">
                                <a href="#" class="font-medium text-cyan-700 hover:text-cyan-900">View all</a>
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
                                            d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 0 1-2.031.352 5.988 5.988 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971Zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 0 1-2.031.352 5.989 5.989 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971Z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="truncate text-sm font-medium text-gray-500">Completed Bookings</dt>
                                        <dd>
                                            <div class="text-lg font-medium text-gray-900">102</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-5 py-3">
                            <div class="text-sm">
                                <a href="#" class="font-medium text-cyan-700 hover:text-cyan-900">View all</a>
                            </div>
                        </div>
                    </div>

                    <!-- More items... -->
                </div>

            </div>

            <h2 class="mx-auto mt-8 max-w-10xl px-4 text-lg/6 font-medium text-gray-900 sm:px-6 lg:px-8">Recent Bookings
            </h2>

            <!-- Activity list (smallest breakpoint only) -->
            <div class="shadow sm:hidden">
                <ul role="list" class="mt-2 divide-y divide-gray-200 overflow-hidden shadow sm:hidden">
                    <li>
                        <a href="#" class="block bg-white px-4 py-4 hover:bg-gray-50">
                            <span class="flex items-center space-x-4">
                                <span class="flex flex-1 space-x-2 truncate">
                                    <svg class="size-5 shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                        aria-hidden="true" data-slot="icon">
                                        <path fill-rule="evenodd"
                                            d="M1 4a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4Zm12 4a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM4 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm13-1a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM1.75 14.5a.75.75 0 0 0 0 1.5c4.417 0 8.693.603 12.749 1.73 1.111.309 2.251-.512 2.251-1.696v-.784a.75.75 0 0 0-1.5 0v.784a.272.272 0 0 1-.35.25A49.043 49.043 0 0 0 1.75 14.5Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <div class="flex flex-col">
                                        <p class="truncate font-bold text-gray-900">Payment to Molly Sanders</p>
                                        <p class="truncate text-xs text-gray-500">Transaction details here</p>
                                    </div>
                                </span>
                                <svg class="size-5 shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                    aria-hidden="true" data-slot="icon">
                                    <path fill-rule="evenodd"
                                        d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                        </a>
                    </li>

                    <!-- More transactions... -->
                </ul>

                <nav class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3"
                    aria-label="Pagination">
                    <div class="flex flex-1 justify-between">
                        <a href="#"
                            class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Previous</a>
                        <a href="#"
                            class="relative ml-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Next</a>
                    </div>
                </nav>
            </div>

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