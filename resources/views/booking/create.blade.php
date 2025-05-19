<x-app-layout>
    <div class="mx-auto flex w-full max-w-10xl flex-col px-4 py-10 sm:px-6 lg:px-8">
        <!-- Header section -->
        <div class="max-w-xl pb-8 space-y-2">
            <!-- Breadcrumb -->
            {{ Breadcrumbs::render('booking.create') }}
            
            <!-- Heading -->
            <h1 id="create-booking-heading" class="text-3xl font-bold tracking-tight text-gray-900">Create Booking</h1>
        </div>

        <!-- Content section with flex layout -->
        <div class="flex items-start gap-x-8">
            <!-- Left column area -->
            <main class="flex-1">
                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="px-4 py-5 sm:p-6">
                        <form action="{{ route('booking.store') }}" method="POST">
                            @csrf
                            <div class="space-y-12">

                                <!-- Shipping Schedule -->
                                <div class="hidden border-b border-gray-900/10 pb-12 space-y-6">
                                    <h2 class="text-base/7 font-semibold text-gray-900">Shipping Schedule</h2>
                                    <div class="md:grid md:grid-cols-3 md:divide-x md:divide-gray-200">
                                        <div class="md:pr-14">
                                            <div class="flex items-center">
                                                <h2 class="flex-auto text-sm font-semibold text-gray-900">January 2022
                                                </h2>
                                                <button type="button"
                                                    class="-my-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500">
                                                    <span class="sr-only">Previous month</span>
                                                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor"
                                                        aria-hidden="true" data-slot="icon">
                                                        <path fill-rule="evenodd"
                                                            d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <button type="button"
                                                    class="-my-1.5 -mr-1.5 ml-2 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500">
                                                    <span class="sr-only">Next month</span>
                                                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor"
                                                        aria-hidden="true" data-slot="icon">
                                                        <path fill-rule="evenodd"
                                                            d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="mt-10 grid grid-cols-7 text-center text-xs/6 text-gray-500">
                                                <div>M</div>
                                                <div>T</div>
                                                <div>W</div>
                                                <div>T</div>
                                                <div>F</div>
                                                <div>S</div>
                                                <div>S</div>
                                            </div>
                                            <div class="mt-2 grid grid-cols-7 text-sm">
                                                <div class="py-2">
                                                    <!--
                                                    Always include: "mx-auto flex size-8 items-center justify-center rounded-full"
                                                    Is selected, include: "text-white"
                                                    Is not selected and is today, include: "text-indigo-600"
                                                    Is not selected and is not today and is current month, include: "text-gray-900"
                                                    Is not selected and is not today and is not current month, include: "text-gray-400"
                                                    Is selected and is today, include: "bg-indigo-600"
                                                    Is selected and is not today, include: "bg-gray-900"
                                                    Is not selected, include: "hover:bg-gray-200"
                                                    Is selected or is today, include: "font-semibold"
                                                    -->
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-400 hover:bg-gray-200">
                                                        <time datetime="2021-12-27">27</time>
                                                    </button>
                                                </div>
                                                <div class="py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-400 hover:bg-gray-200">
                                                        <time datetime="2021-12-28">28</time>
                                                    </button>
                                                </div>
                                                <div class="py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-400 hover:bg-gray-200">
                                                        <time datetime="2021-12-29">29</time>
                                                    </button>
                                                </div>
                                                <div class="py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-400 hover:bg-gray-200">
                                                        <time datetime="2021-12-30">30</time>
                                                    </button>
                                                </div>
                                                <div class="py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-400 hover:bg-gray-200">
                                                        <time datetime="2021-12-31">31</time>
                                                    </button>
                                                </div>
                                                <div class="py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-01">1</time>
                                                    </button>
                                                </div>
                                                <div class="py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-02">2</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-03">3</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-04">4</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-05">5</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-06">6</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-07">7</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-08">8</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-09">9</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-10">10</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-11">11</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full font-semibold text-indigo-600 hover:bg-gray-200">
                                                        <time datetime="2022-01-12">12</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-13">13</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-14">14</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-15">15</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-16">16</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-17">17</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-18">18</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-19">19</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-20">20</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full bg-gray-900 font-semibold text-white">
                                                        <time datetime="2022-01-21">21</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-22">22</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-23">23</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-24">24</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-25">25</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-26">26</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-27">27</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-28">28</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-29">29</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-30">30</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-900 hover:bg-gray-200">
                                                        <time datetime="2022-01-31">31</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-400 hover:bg-gray-200">
                                                        <time datetime="2022-02-01">1</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-400 hover:bg-gray-200">
                                                        <time datetime="2022-02-02">2</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-400 hover:bg-gray-200">
                                                        <time datetime="2022-02-03">3</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-400 hover:bg-gray-200">
                                                        <time datetime="2022-02-04">4</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-400 hover:bg-gray-200">
                                                        <time datetime="2022-02-05">5</time>
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-200 py-2">
                                                    <button type="button"
                                                        class="mx-auto flex size-8 items-center justify-center rounded-full text-gray-400 hover:bg-gray-200">
                                                        <time datetime="2022-02-06">6</time>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <section class="mt-12 md:mt-0 md:pl-14 col-span-2 space-y-4">
                                            <h2 class="text-base font-semibold text-gray-900">Schedule for <time
                                                    datetime="2022-01-21">January 21, 2022</time></h2>
                                            <ul role="list" class="space-y-4">
                                                <li
                                                    class="flex items-center justify-between gap-x-6 py-5 border border-gray-200 rounded-lg p-4">
                                                    <div class="min-w-0">
                                                        <div class="flex items-start gap-x-3">
                                                            <p class="text-sm/6 font-semibold text-gray-900">MAERSK
                                                                Sailing Ship
                                                            </p> <span
                                                                class="rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Voyage
                                                                No: ECG 441</span>
                                                        </div>
                                                        <div
                                                            class="mt-1 flex items-center gap-x-2 text-xs/5 text-gray-500">
                                                            <p class="whitespace-nowrap">Singapore | <time
                                                                    datetime="2023-03-17T00:00Z">8:00 AM</time>
                                                            </p>
                                                            <p>→</p>
                                                            <p class="truncate">Johor Port | <time
                                                                    datetime="2023-03-17T00:00Z">01:00 PM</time>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-none items-center gap-x-4">
                                                        <a href="#"
                                                            class="hidden rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:block">View
                                                            details
                                                            <span class="sr-only">, GraphQL API</span></a>
                                                    </div>
                                                </li>

                                                <li
                                                    class="flex items-center justify-between gap-x-6 py-5 border border-gray-200 rounded-lg p-4">
                                                    <div class="min-w-0">
                                                        <div class="flex items-start gap-x-3">
                                                            <p class="text-sm/6 font-semibold text-gray-900">MAERSK
                                                                Sailing Ship
                                                            </p> <span
                                                                class="rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Voyage
                                                                No: ECG 441</span>
                                                        </div>
                                                        <div
                                                            class="mt-1 flex items-center gap-x-2 text-xs/5 text-gray-500">
                                                            <p class="whitespace-nowrap">Singapore | <time
                                                                    datetime="2023-03-17T00:00Z">8:00 AM</time>
                                                            </p>
                                                            <p>→</p>
                                                            <p class="truncate">Johor Port | <time
                                                                    datetime="2023-03-17T00:00Z">01:00 PM</time>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-none items-center gap-x-4">
                                                        <a href="#"
                                                            class="hidden rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:block">View
                                                            details
                                                            <span class="sr-only">, GraphQL API</span></a>
                                                    </div>
                                                </li>

                                                <li
                                                    class="flex items-center justify-between gap-x-6 py-5 border border-gray-200 rounded-lg p-4">
                                                    <div class="min-w-0">
                                                        <div class="flex items-start gap-x-3">
                                                            <p class="text-sm/6 font-semibold text-gray-900">MAERSK
                                                                Sailing Ship
                                                            </p> <span
                                                                class="rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Voyage
                                                                No: ECG 441</span>
                                                        </div>
                                                        <div
                                                            class="mt-1 flex items-center gap-x-2 text-xs/5 text-gray-500">
                                                            <p class="whitespace-nowrap">Singapore | <time
                                                                    datetime="2023-03-17T00:00Z">8:00 AM</time>
                                                            </p>
                                                            <p>→</p>
                                                            <p class="truncate">Johor Port | <time
                                                                    datetime="2023-03-17T00:00Z">01:00 PM</time>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-none items-center gap-x-4">
                                                        <a href="#"
                                                            class="hidden rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:block">View
                                                            details
                                                            <span class="sr-only">, GraphQL API</span></a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </section>
                                    </div>
                                </div>

                                <!-- Schedule Information -->
                                <div class="border-b border-gray-900/10 pb-12 space-y-6">
                                    <h2 class="text-base/7 font-semibold text-gray-900">Schedule Information</h2>
                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <x-input-label for="ets" value="Estimated Time of Sailing (ETS)" />
                                            <x-text-input id="ets" name="ets" type="datetime-local" class="mt-1 block w-full" />
                                            @error('ets')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="sm:col-span-3">
                                            <x-input-label for="route" value="Route" />
                                            <livewire:shipping-route-dropdown />
                                        </div>
                                    </div>
                                </div>

                                <!-- Route Information -->
                                <div class="border-b border-gray-900/10 pb-12 space-y-6">
                                    <h2 class="text-base/7 font-semibold text-gray-900">Route Information</h2>
                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <x-input-label for="place_of_receipt" value="Place of Receipt" />
                                            <x-text-input id="place_of_receipt" name="place_of_receipt" type="text" class="mt-1 block w-full" required/>
                                            @error('place_of_receipt')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="sm:col-span-3">
                                            <x-input-label for="pol" value="Port of Loading (POL)" />
                                            <x-text-input id="pol" name="pol" type="text" class="mt-1 block w-full" required/>
                                            @error('pol')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="sm:col-span-3">
                                            <x-input-label for="pod" value="Port of Discharge (POD)" />
                                            <x-text-input id="pod" name="pod" type="text" class="mt-1 block w-full" required/>
                                            @error('pod')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="sm:col-span-3">
                                            <x-input-label for="place_of_delivery" value="Place of Delivery" />
                                            <x-text-input id="place_of_delivery" name="place_of_delivery" type="text" class="mt-1 block w-full" required/>
                                            @error('place_of_delivery')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                                <!-- Cargo Information -->
                                <div class="border-b border-gray-900/10 pb-12 space-y-6">
                                    <h2 class="text-base/7 font-semibold text-gray-900">Cargo Information</h2>

                                    <div class="mt-8 flow-root">
                                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                                <table class="min-w-full divide-y divide-gray-300">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col"
                                                                class="w-1/3 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                                                Type</th>
                                                            <th scope="col"
                                                                class="w-1/3 px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                                Number of Cargo</th>
                                                            <th scope="col"
                                                                class="w-1/3 px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                                Total Weight (kg)</th>
                                                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                                                <span class="sr-only">Edit</span>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200" id="cargo-tbody">
                                                        <tr>
                                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                                                <select name="container_type[]" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" onchange="updateContainerOptions(this)">
                                                                    <option value="">Select container type</option>
                                                                    <option value="40HC">40' High Cube</option>
                                                                    <option value="20DC">20' Dry Cargo</option>
                                                                </select>
                                                            </td>
                                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                                <x-text-input name="container_count[]" type="number" min="1" class="block w-full" />
                                                            </td>
                                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                                <x-text-input name="total_weight[]" type="number" step="0.01" class="block w-full" />
                                                            </td>
                                                            <td class="text-center relative whitespace-nowrap py-4 pl-3 pr-4 text-sm font-medium sm:pr-0">
                                                                <button type="button" class="text-red-600 hover:text-red-900 delete-row" onclick="deleteRow(this)">
                                                                    <span class="sr-only">Delete</span>
                                                                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                        <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                                                                    </svg>
                                                                </button>
                                                            </td>
                                                            <td class="text-center relative whitespace-nowrap py-4 pl-3 pr-4 text-sm font-medium sm:pr-0 text-gray-400">
                                                                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="addNewRow()">
                                                                    <span class="sr-only">Add</span>
                                                                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                                                    </svg>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit button -->
                                <div class="mt-6 flex items-center justify-end gap-x-6">
                                    <button type="button" onclick="window.history.back()" class="text-sm/6 font-semibold text-gray-900">Back</button>
                                    <button type="submit"
                                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                        Save Draft
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>

<script>
function addNewRow() {
    const tbody = document.getElementById('cargo-tbody');
    const template = tbody.querySelector('tr').cloneNode(true);
    
    // Clear input values
    template.querySelectorAll('input').forEach(input => {
        input.value = '';
        // Update name attributes to ensure they maintain array format
        const name = input.getAttribute('name');
        if (name) {
            const index = tbody.querySelectorAll('tr').length;
            input.setAttribute('name', name.replace(/\[\d*\]/, `[${index}]`));
        }
    });
    
    // Update select name attribute and reset options
    const select = template.querySelector('select');
    if (select) {
        const index = tbody.querySelectorAll('tr').length;
        select.value = '';
        select.setAttribute('name', select.getAttribute('name').replace(/\[\d*\]/, `[${index}]`));
        
        // Reset all options to visible
        Array.from(select.options).forEach(option => {
            if (option.value !== '') {
                option.style.display = '';
            }
        });
        
        // Hide already selected options
        const selectedValues = Array.from(tbody.querySelectorAll('select[name^="container_type"]'))
            .map(select => select.value)
            .filter(value => value !== '');
            
        selectedValues.forEach(value => {
            const option = select.querySelector(`option[value="${value}"]`);
            if (option) {
                option.style.display = 'none';
            }
        });
    }
    
    tbody.appendChild(template);
}

function deleteRow(button) {
    const tbody = document.getElementById('cargo-tbody');
    if (tbody.querySelectorAll('tr').length > 1) {
        const deletedValue = button.closest('tr').querySelector('select').value;
        button.closest('tr').remove();
        
        // Reindex remaining rows
        tbody.querySelectorAll('tr').forEach((row, index) => {
            row.querySelectorAll('[name]').forEach(input => {
                const name = input.getAttribute('name');
                input.setAttribute('name', name.replace(/\[\d*\]/, `[${index}]`));
            });
        });
        
        // Show the deleted option in all other selects
        if (deletedValue) {
            tbody.querySelectorAll('select[name^="container_type"]').forEach(select => {
                const option = select.querySelector(`option[value="${deletedValue}"]`);
                if (option) {
                    option.style.display = '';
                }
            });
        }
    }
}

function updateContainerOptions(changedSelect) {
    const tbody = document.getElementById('cargo-tbody');
    const selectedValue = changedSelect.value;
    
    // If a value was selected, hide it in other selects
    if (selectedValue) {
        tbody.querySelectorAll('select[name^="container_type"]').forEach(select => {
            if (select !== changedSelect) {
                const option = select.querySelector(`option[value="${selectedValue}"]`);
                if (option) {
                    option.style.display = 'none';
                }
            }
        });
    }
}

// Listen for route selection event
document.addEventListener('livewire:initialized', function () {
    console.log('Livewire initialized, setting up routeSelected listener');
    
    // Function to populate form fields
    function populateFormFields(data) {
        console.log('Populating form fields with data:', data);
        
        const placeOfReceipt = document.getElementById('place_of_receipt');
        const pol = document.getElementById('pol');
        const pod = document.getElementById('pod');
        const placeOfDelivery = document.getElementById('place_of_delivery');
        
        if (placeOfReceipt) placeOfReceipt.value = data.place_of_receipt;
        if (pol) pol.value = data.pol;
        if (pod) pod.value = data.pod;
        if (placeOfDelivery) placeOfDelivery.value = data.place_of_delivery;
        
        console.log('Form fields populated');
    }
    
    // Listen for Livewire 3 events
    Livewire.on('routeSelected', function (data) {
        console.log('Livewire 3 routeSelected event received:', data);
        populateFormFields(data);
    });
    
    // Listen for Livewire 2 events
    window.addEventListener('routeSelected', function (event) {
        console.log('Livewire 2 routeSelected event received:', event.detail);
        populateFormFields(event.detail);
    });
});

// Remove the modal show/hide logic since we're using proper form submission
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate required fields - updated to match actual form fields
    const required = ['place_of_receipt', 'pol', 'pod', 'place_of_delivery', 'ets'];
    let isValid = true;
    
    required.forEach(field => {
        const input = this.querySelector(`[name="${field}"]`);
        if (!input || !input.value) {
            isValid = false;
            // Add error styling if needed
            if (input) {
                input.classList.add('border-red-500');
            }
        }
    });
    
    // Validate cargo table has at least one valid row
    const cargoRows = document.querySelectorAll('#cargo-tbody tr');
    let hasValidCargo = false;
    
    cargoRows.forEach(row => {
        const type = row.querySelector('[name^="container_type"]').value;
        const count = row.querySelector('[name^="container_count"]').value;
        const weight = row.querySelector('[name^="total_weight"]').value;
        
        if (type && count && weight) {
            hasValidCargo = true;
        }
    });
    
    if (!hasValidCargo) {
        isValid = false;
        // Add error styling to cargo table if needed
    }
    
    if (isValid) {
        this.submit();
    } else {
        alert('Please fill in all required fields');
    }
});
</script>