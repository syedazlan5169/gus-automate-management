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
                                <svg class="size-5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                                    <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 0 1 1.414 0l7 7A1 1 0 0 1 17 11h-1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6H3a1 1 0 0 1-.707-1.707l7-7Z" clip-rule="evenodd" />
                                </svg>
                                <span class="sr-only">Home</span>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="size-5 shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                                <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('bookings.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Bookings</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="size-5 shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                                <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500" aria-current="page">Edit Booking</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Heading -->
            <h1 id="edit-booking-heading" class="text-3xl font-bold tracking-tight text-gray-900">Edit Booking</h1>
        </div>

        <!-- Content section with flex layout -->
        <div class="flex items-start gap-x-8">
            <!-- Left column area -->
            <main class="flex-1">
                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="px-4 py-5 sm:p-6">
                        <form action="{{ route('booking.update', $booking) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="space-y-12">
                                <!-- Service Information -->
                                <!--<div class="border-b border-gray-900/10 pb-12 space-y-6">
                                    <fieldset>
                                        <h2 class="text-base/7 font-semibold text-gray-900">Service Information</h2>
                                        <div class="mt-6 flex gap-x-6">
                                            <div class="flex items-center gap-x-3">
                                                <input id="soc" name="service" type="radio" value="SOC" {{ $booking->service === 'SOC' ? 'checked' : '' }}
                                                    class="relative size-4 appearance-none rounded-full border border-gray-300 bg-white before:absolute before:inset-1 before:rounded-full before:bg-white checked:border-indigo-600 checked:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                                <label for="soc" class="block text-sm/6 font-medium text-gray-900">
                                                    Shipped Owned Container (SOC)
                                                </label>
                                            </div>
                                            <div class="flex items-center gap-x-3">
                                                <input id="coc" name="service" type="radio" value="COC" {{ $booking->service === 'COC' ? 'checked' : '' }}
                                                    class="relative size-4 appearance-none rounded-full border border-gray-300 bg-white before:absolute before:inset-1 before:rounded-full before:bg-white checked:border-indigo-600 checked:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                                <label for="coc" class="block text-sm/6 font-medium text-gray-900">
                                                    Carrier Owned Container (COC)
                                                </label>
                                            </div>
                                        </div>
                                        @error('service')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </fieldset>
                                </div>-->

                                <!-- Shipping Details -->
                                <div class="border-b border-gray-900/10 pb-12 space-y-6">
                                    <h2 class="text-base/7 font-semibold text-gray-900">Shipping Details</h2>
                                    
                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <x-input-label for="vessel" value="Vessel Name" />
                                            <x-text-input id="vessel" name="vessel" type="text" class="mt-1 block w-full" 
                                                value="{{ old('vessel', $booking->vessel) }}" required />
                                        </div>

                                        <div class="sm:col-span-3">
                                            <x-input-label for="voyage" value="Voyage Number" />
                                            <x-text-input id="voyage" name="voyage" type="text" class="mt-1 block w-full"
                                                value="{{ old('voyage', $booking->voyage) }}" required />
                                        </div>
                                    </div>
                                </div>

                                <!-- Route Information -->
                                <div class="border-b border-gray-900/10 pb-12 space-y-6">
                                    <h2 class="text-base/7 font-semibold text-gray-900">Route Information</h2>
                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <x-input-label for="place_of_receipt" value="Place of Receipt" />
                                            <x-text-input id="place_of_receipt" name="place_of_receipt" type="text" class="mt-1 block w-full"
                                                value="{{ old('place_of_receipt', $booking->place_of_receipt) }}" />
                                        </div>

                                        <div class="sm:col-span-3">
                                            <x-input-label for="pol" value="Port of Loading (POL)" />
                                            <x-text-input id="pol" name="pol" type="text" class="mt-1 block w-full"
                                                value="{{ old('pol', $booking->pol) }}" />
                                        </div>

                                        <div class="sm:col-span-3">
                                            <x-input-label for="pod" value="Port of Discharge (POD)" />
                                            <x-text-input id="pod" name="pod" type="text" class="mt-1 block w-full"
                                                value="{{ old('pod', $booking->pod) }}" />
                                        </div>

                                        <div class="sm:col-span-3">
                                            <x-input-label for="place_of_delivery" value="Place of Delivery" />
                                            <x-text-input id="place_of_delivery" name="place_of_delivery" type="text" class="mt-1 block w-full"
                                                value="{{ old('place_of_delivery', $booking->place_of_delivery) }}" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Schedule Information -->
                                <div class="border-b border-gray-900/10 pb-12 space-y-6">
                                    <h2 class="text-base/7 font-semibold text-gray-900">Schedule Information</h2>
                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <x-input-label for="ets" value="Estimated Time of Sailing (ETS)" />
                                            <x-text-input id="ets" name="ets" type="datetime-local" class="mt-1 block w-full"
                                                value="{{ old('ets', $booking->ets?->format('Y-m-d\TH:i')) }}" />
                                        </div>

                                        <div class="sm:col-span-3">
                                            <x-input-label for="eta" value="Estimated Time of Arrival (ETA)" />
                                            <x-text-input id="eta" name="eta" type="datetime-local" class="mt-1 block w-full"
                                                value="{{ old('eta', $booking->eta?->format('Y-m-d\TH:i')) }}" />
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
                                                            <th scope="col" class="w-1/3 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Container Type</th>
                                                            <th scope="col" class="w-1/3 px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Number of Containers</th>
                                                            <th scope="col" class="w-1/3 px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total Weight (kg)</th>
                                                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                                                <span class="sr-only">Actions</span>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200" id="cargo-tbody">
                                                        @forelse($booking->cargos as $index => $cargo)
                                                        <tr>
                                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                                                <select name="container_type[]" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300">
                                                                    <option value="">Select container type</option>
                                                                    <option value="20GP" {{ $cargo->container_type === '20GP' ? 'selected' : '' }}>20' General Purpose</option>
                                                                    <option value="40GP" {{ $cargo->container_type === '40GP' ? 'selected' : '' }}>40' General Purpose</option>
                                                                    <option value="40HC" {{ $cargo->container_type === '40HC' ? 'selected' : '' }}>40' High Cube</option>
                                                                    <option value="20RF" {{ $cargo->container_type === '20RF' ? 'selected' : '' }}>20' Reefer</option>
                                                                    <option value="40RF" {{ $cargo->container_type === '40RF' ? 'selected' : '' }}>40' Reefer</option>
                                                                </select>
                                                            </td>
                                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                                <x-text-input name="container_count[]" type="number" min="1" class="block w-full" 
                                                                    value="{{ old('container_count.' . $index, $cargo->container_count) }}" />
                                                            </td>
                                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                                <x-text-input name="total_weight[]" type="number" step="0.01" class="block w-full"
                                                                    value="{{ old('total_weight.' . $index, $cargo->total_weight) }}" />
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
                                                        @empty
                                                        <tr>
                                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                                                <select name="container_type[]" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300">
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
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit button -->
                                <div class="mt-6 flex items-center justify-end gap-x-6">
                                    <button onclick="window.history.back()"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                        Back
                                    </button>
                                    <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                        Update Booking
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>

            <!-- Right column area -->
            <aside class="sticky top-8 hidden w-96 shrink-0 xl:block">
                <!-- ... same progress indicator as in create.blade.php ... -->
            </aside>
        </div>
    </div>
</x-app-layout>

<script>
// ... same JavaScript functions as in create.blade.php ...
</script>
