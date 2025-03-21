<x-app-layout>
    <div class="mx-auto flex w-full max-w-10xl flex-col px-4 py-10 sm:px-6 lg:px-8">
        <!-- Header section -->
        <div class="max-w-xl pb-8 space-y-2">
            <!-- Breadcrumb -->
            <nav class="flex" aria-label="Breadcrumb">
                <ol role="list" class="flex items-center space-x-4">
                    <li>
                        <div>
                            <a href="#" class="text-gray-400 hover:text-gray-500">
                                <svg class="size-5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                                    data-slot="icon">
                                    <path fill-rule="evenodd"
                                        d="M9.293 2.293a1 1 0 0 1 1.414 0l7 7A1 1 0 0 1 17 11h-1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6H3a1 1 0 0 1-.707-1.707l7-7Z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="sr-only">Home</span>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="size-5 shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                aria-hidden="true" data-slot="icon">
                                <path fill-rule="evenodd"
                                    d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <a href="#" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Projects</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="size-5 shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                aria-hidden="true" data-slot="icon">
                                <path fill-rule="evenodd"
                                    d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <a href="#" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700"
                                aria-current="page">Project
                                Nero</a>
                        </div>
                    </li>
                </ol>
            </nav>

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
                                <!-- Service Information -->
                                <div class="border-b border-gray-900/10 pb-12 space-y-6">
                                    <fieldset>
                                        <h2 class="text-base/7 font-semibold text-gray-900">Service Information</h2>
                                        <div class="mt-6 flex gap-x-6">
                                            <div class="flex items-center gap-x-3">
                                                <input id="soc" name="service" type="radio" value="SOC" checked
                                                    class="relative size-4 appearance-none rounded-full border border-gray-300 bg-white before:absolute before:inset-1 before:rounded-full before:bg-white checked:border-indigo-600 checked:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:before:bg-gray-400 forced-colors:appearance-auto forced-colors:before:hidden [&:not(:checked)]:before:hidden">
                                                <label for="soc" class="block text-sm/6 font-medium text-gray-900">
                                                    Shipped Owned Container (SOC)
                                                </label>
                                            </div>
                                            <div class="flex items-center gap-x-3">
                                                <input id="coc" name="service" type="radio" value="COC"
                                                    class="relative size-4 appearance-none rounded-full border border-gray-300 bg-white before:absolute before:inset-1 before:rounded-full before:bg-white checked:border-indigo-600 checked:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:before:bg-gray-400 forced-colors:appearance-auto forced-colors:before:hidden [&:not(:checked)]:before:hidden">
                                                <label for="coc" class="block text-sm/6 font-medium text-gray-900">
                                                    Carrier Owned Container (COC)
                                                </label>
                                            </div>
                                        </div>
                                        @error('service')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </fieldset>
                                </div>

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

                                <!-- Shipping Details -->
                                <div class="border-b border-gray-900/10 pb-12 space-y-6">
                                    <h2 class="text-base/7 font-semibold text-gray-900">Shipping Details</h2>
                                    
                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <x-input-label for="vessel" value="Vessel Name" />
                                            <x-text-input id="vessel" name="vessel" type="text" class="mt-1 block w-full" />
                                        </div>

                                        <div class="sm:col-span-3">
                                            <x-input-label for="voyage" value="Voyage Number" />
                                            <x-text-input id="voyage" name="voyage" type="text" class="mt-1 block w-full" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Route Information -->
                                <div class="border-b border-gray-900/10 pb-12 space-y-6">
                                    <h2 class="text-base/7 font-semibold text-gray-900">Route Information</h2>
                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <x-input-label for="place_of_receipt" value="Place of Receipt" />
                                            <x-text-input id="place_of_receipt" name="place_of_receipt" type="text" class="mt-1 block w-full" />
                                        </div>

                                        <div class="sm:col-span-3">
                                            <x-input-label for="pol" value="Port of Loading (POL)" />
                                            <x-text-input id="pol" name="pol" type="text" class="mt-1 block w-full" />
                                        </div>

                                        <div class="sm:col-span-3">
                                            <x-input-label for="pod" value="Port of Discharge (POD)" />
                                            <x-text-input id="pod" name="pod" type="text" class="mt-1 block w-full" />
                                        </div>

                                        <div class="sm:col-span-3">
                                            <x-input-label for="place_of_delivery" value="Place of Delivery" />
                                            <x-text-input id="place_of_delivery" name="place_of_delivery" type="text" class="mt-1 block w-full" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Schedule Information -->
                                <div class="border-b border-gray-900/10 pb-12 space-y-6">
                                    <h2 class="text-base/7 font-semibold text-gray-900">Schedule Information</h2>
                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <x-input-label for="ets" value="Estimated Time of Sailing (ETS)" />
                                            <x-text-input id="ets" name="ets" type="datetime-local" class="mt-1 block w-full" />
                                        </div>

                                        <div class="sm:col-span-3">
                                            <x-input-label for="eta" value="Estimated Time of Arrival (ETA)" />
                                            <x-text-input id="eta" name="eta" type="datetime-local" class="mt-1 block w-full" />
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
                                                                Container Type</th>
                                                            <th scope="col"
                                                                class="w-1/3 px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                                Number of Containers</th>
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
                                                                <select name="container_type[]" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                                                    <option value="">Select container type</option>
                                                                    <option value="20GP">20' General Purpose</option>
                                                                    <option value="40GP">40' General Purpose</option>
                                                                    <option value="40HC">40' High Cube</option>
                                                                    <option value="20RF">20' Reefer</option>
                                                                    <option value="40RF">40' Reefer</option>
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

                                <!-- Modal -->
                                <div id="confirm-modal" class="hidden relative z-10" aria-labelledby="modal-title"
                                    role="dialog" aria-modal="true">
                                    <!--
                                    Background backdrop, show/hide based on modal state.

                                    Entering: "ease-out duration-300"
                                    From: "opacity-0"
                                    To: "opacity-100"
                                    Leaving: "ease-in duration-200"
                                    From: "opacity-100"
                                    To: "opacity-0"
                                -->
                                    <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true">
                                    </div>

                                    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                        <div
                                            class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                            <!--
                                            Modal panel, show/hide based on modal state.

                                            Entering: "ease-out duration-300"
                                            From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                            To: "opacity-100 translate-y-0 sm:scale-100"
                                            Leaving: "ease-in duration-200"
                                            From: "opacity-100 translate-y-0 sm:scale-100"
                                            To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                        -->
                                            <div
                                                class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
                                                <div>
                                                    <div
                                                        class="mx-auto flex size-12 items-center justify-center rounded-full bg-green-100">
                                                        <svg class="size-6 text-green-600" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            aria-hidden="true" data-slot="icon">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="m4.5 12.75 6 6 9-13.5" />
                                                        </svg>
                                                    </div>
                                                    <div class="mt-3 text-center sm:mt-5">
                                                        <h3 class="text-base font-semibold text-gray-900"
                                                            id="modal-title">Booking Created Successfully</h3>
                                                    </div>
                                                </div>
                                                <div class="mt-5 sm:mt-6">
                                                    <button type="button"
                                                        onclick="document.getElementById('confirm-modal').classList.add('hidden')"
                                                        class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">View
                                                        my bookings</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit button -->
                                <div class="mt-6 flex items-center justify-end gap-x-6">
                                    <button type="button" onclick="window.history.back()" class="text-sm/6 font-semibold text-gray-900">Cancel</button>
                                    <button type="submit"
                                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                        Submit Booking
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>

            <!-- Right column area -->
            <aside class="sticky top-8 hidden w-96 shrink-0 xl:block">
                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="px-4 py-5 sm:p-6">
                        <nav aria-label="Progress">
                            <ol role="list" class="overflow-hidden">
                                <li class="relative pb-10">
                                    <div class="absolute left-4 top-4 -ml-px mt-0.5 h-full w-0.5 bg-indigo-600"
                                        aria-hidden="true"></div>
                                    <!-- Complete Step -->
                                    <a href="#" class="group relative flex items-start">
                                        <span class="flex h-9 items-center">
                                            <span
                                                class="relative z-10 flex size-8 items-center justify-center rounded-full bg-indigo-600 group-hover:bg-indigo-800">
                                                <svg class="size-5 text-white" viewBox="0 0 20 20" fill="currentColor"
                                                    aria-hidden="true" data-slot="icon">
                                                    <path fill-rule="evenodd"
                                                        d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </span>
                                        <span class="ml-4 flex min-w-0 flex-col">
                                            <span class="text-sm font-medium">Service Information</span>
                                            <span class="text-sm text-gray-500">Select type of service.</span>
                                        </span>
                                    </a>
                                </li>
                                <li class="relative pb-10">
                                    <div class="absolute left-4 top-4 -ml-px mt-0.5 h-full w-0.5 bg-gray-300"
                                        aria-hidden="true"></div>
                                    <!-- Current Step -->
                                    <a href="#" class="group relative flex items-start" aria-current="step">
                                        <span class="flex h-9 items-center" aria-hidden="true">
                                            <span
                                                class="relative z-10 flex size-8 items-center justify-center rounded-full border-2 border-indigo-600 bg-white">
                                                <span class="size-2.5 rounded-full bg-indigo-600"></span>
                                            </span>
                                        </span>
                                        <span class="ml-4 flex min-w-0 flex-col">
                                            <span class="text-sm font-medium text-indigo-600">Shipping Schedule</span>
                                            <span class="text-sm text-gray-500">Select sailing date.</span>
                                        </span>
                                    </a>
                                </li>
                                <li class="relative pb-10">
                                    <div class="absolute left-4 top-4 -ml-px mt-0.5 h-full w-0.5 bg-gray-300"
                                        aria-hidden="true"></div>
                                    <!-- Upcoming Step -->
                                    <a href="#" class="group relative flex items-start">
                                        <span class="flex h-9 items-center" aria-hidden="true">
                                            <span
                                                class="relative z-10 flex size-8 items-center justify-center rounded-full border-2 border-gray-300 bg-white group-hover:border-gray-400">
                                                <span
                                                    class="size-2.5 rounded-full bg-transparent group-hover:bg-gray-300"></span>
                                            </span>
                                        </span>
                                        <span class="ml-4 flex min-w-0 flex-col">
                                            <span class="text-sm font-medium text-gray-500">Route Information</span>
                                            <span class="text-sm text-gray-500">Provide route details.</span>
                                        </span>
                                    </a>
                                </li>
                                <li class="relative pb-10">
                                    <div class="absolute left-4 top-4 -ml-px mt-0.5 h-full w-0.5 bg-gray-300"
                                        aria-hidden="true"></div>
                                    <!-- Upcoming Step -->
                                    <a href="#" class="group relative flex items-start">
                                        <span class="flex h-9 items-center" aria-hidden="true">
                                            <span
                                                class="relative z-10 flex size-8 items-center justify-center rounded-full border-2 border-gray-300 bg-white group-hover:border-gray-400">
                                                <span
                                                    class="size-2.5 rounded-full bg-transparent group-hover:bg-gray-300"></span>
                                            </span>
                                        </span>
                                        <span class="ml-4 flex min-w-0 flex-col">
                                            <span class="text-sm font-medium text-gray-500">Schedule Information</span>
                                            <span class="text-sm text-gray-500">Select ETS and ETA.</span>
                                        </span>
                                    </a>
                                </li>
                                <li class="relative">
                                    <!-- Upcoming Step -->
                                    <a href="#" class="group relative flex items-start">
                                        <span class="flex h-9 items-center" aria-hidden="true">
                                            <span
                                                class="relative z-10 flex size-8 items-center justify-center rounded-full border-2 border-gray-300 bg-white group-hover:border-gray-400">
                                                <span
                                                    class="size-2.5 rounded-full bg-transparent group-hover:bg-gray-300"></span>
                                            </span>
                                        </span>
                                        <span class="ml-4 flex min-w-0 flex-col">
                                            <span class="text-sm font-medium text-gray-500">Cargo Information</span>
                                            <span class="text-sm text-gray-500">Provide cargo details.</span>
                                        </span>
                                    </a>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </aside>
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
    
    // Update select name attribute
    const select = template.querySelector('select');
    if (select) {
        const index = tbody.querySelectorAll('tr').length;
        select.value = '';
        select.setAttribute('name', select.getAttribute('name').replace(/\[\d*\]/, `[${index}]`));
    }
    
    tbody.appendChild(template);
}

function deleteRow(button) {
    const tbody = document.getElementById('cargo-tbody');
    if (tbody.querySelectorAll('tr').length > 1) {
        button.closest('tr').remove();
        // Reindex remaining rows
        tbody.querySelectorAll('tr').forEach((row, index) => {
            row.querySelectorAll('[name]').forEach(input => {
                const name = input.getAttribute('name');
                input.setAttribute('name', name.replace(/\[\d*\]/, `[${index}]`));
            });
        });
    }
}

// Remove the modal show/hide logic since we're using proper form submission
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate required fields
    const required = ['service', 'vessel', 'voyage', 'place_of_receipt', 'pol', 'pod', 'place_of_delivery', 'ets', 'eta'];
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