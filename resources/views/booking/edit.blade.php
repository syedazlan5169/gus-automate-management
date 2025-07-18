<x-app-layout>
    <div class="mx-auto flex w-full max-w-10xl flex-col px-4 py-10 sm:px-6 lg:px-8">
        <!-- Header section -->
        <div class="max-w-xl pb-8 space-y-2">
            <!-- Breadcrumb -->
            {{ Breadcrumbs::render('booking.edit', $booking) }}

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
                            
                            @if ($errors->any())
                                <div class="mb-6 rounded-md bg-red-50 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-red-800">There were {{ $errors->count() }} errors with your submission</h3>
                                            <div class="mt-2 text-sm text-red-700">
                                                <ul role="list" class="list-disc space-y-1 pl-5">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="space-y-12">

                                <!-- Shipping Details -->
                                @if (auth()->user()->role != 'customer')
                                <div class="border-b border-gray-900/10 pb-12 space-y-6">
                                    <h2 class="text-base/7 font-semibold text-gray-900">Shipping Details</h2>
                                    
                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <x-input-label for="vessel" value="Vessel Name" />
                                            <x-text-input id="vessel" name="vessel" type="text" class="mt-1 block w-full" 
                                                value="{{ old('vessel', $booking->vessel) }}" required />
                                            @error('vessel')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="sm:col-span-3">
                                            <x-input-label for="voyage" value="Voyage Number" />
                                            <x-text-input id="voyage" name="voyage" type="text" class="mt-1 block w-full"
                                                value="{{ old('voyage', $booking->voyage->voyage_number ?? '') }}" required />
                                            @error('voyage')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <x-input-label for="tug" value="Tug" />
                                            <x-text-input id="tug" name="tug" type="text" class="mt-1 block w-full" 
                                                value="{{ old('tug', $booking->tug) }}" required />
                                            @error('tug')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="sm:col-span-3">
                                            <x-input-label for="delivery_terms" value="Delivery Terms" />
                                            <x-text-input id="delivery_terms" name="delivery_terms" type="text" class="mt-1 block w-full"
                                                value="{{ old('delivery_terms', $booking->delivery_terms) }}" required />
                                            @error('delivery_terms')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Route Information -->
                                <div class="border-b border-gray-900/10 pb-12 space-y-6">
                                    <h2 class="text-base/7 font-semibold text-gray-900">Route Information</h2>
                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <x-input-label for="place_of_receipt" value="Place of Receipt" />
                                            <x-text-input id="place_of_receipt" name="place_of_receipt" type="text" class="mt-1 block w-full"
                                                value="{{ old('place_of_receipt', $booking->place_of_receipt) }}" required />
                                            @error('place_of_receipt')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="sm:col-span-3">
                                            <x-input-label for="pol" value="Port of Loading (POL)" />
                                            <x-text-input id="pol" name="pol" type="text" class="mt-1 block w-full"
                                                value="{{ old('pol', $booking->pol) }}" required />
                                            @error('pol')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="sm:col-span-3">
                                            <x-input-label for="pod" value="Port of Discharge (POD)" />
                                            <x-text-input id="pod" name="pod" type="text" class="mt-1 block w-full"
                                                value="{{ old('pod', $booking->pod) }}" required />
                                            @error('pod')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="sm:col-span-3">
                                            <x-input-label for="place_of_delivery" value="Place of Delivery" />
                                            <x-text-input id="place_of_delivery" name="place_of_delivery" type="text" class="mt-1 block w-full"
                                                value="{{ old('place_of_delivery', $booking->place_of_delivery) }}" required />
                                            @error('place_of_delivery')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
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
                                                value="{{ old('ets', $booking->ets?->format('Y-m-d\TH:i')) }}" required/>
                                            @error('ets')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        @if (auth()->user()->role != 'customer')
                                        <div class="sm:col-span-3">
                                            <x-input-label for="eta" value="Estimated Time of Arrival (ETA)" />
                                            <x-text-input id="eta" name="eta" type="datetime-local" class="mt-1 block w-full"
                                                value="{{ old('eta', $booking->eta?->format('Y-m-d\TH:i')) }}" required/>
                                            @error('eta')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        @endif
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
                                                                    <option value="40HC" {{ $cargo->container_type === '40HC' ? 'selected' : '' }}>40' High Cube</option>
                                                                    <option value="20DC" {{ $cargo->container_type === '20DC' ? 'selected' : '' }}>20' Dry Cargo</option>
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
function addNewRow() {
    const tbody = document.getElementById('cargo-tbody');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
            <select name="container_type[]" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300">
                <option value="">Select container type</option>
                <option value="40HC">40' High Cube</option>
                <option value="20DC">20' Dry Cargo</option>
            </select>
        </td>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
            <input type="number" name="container_count[]" min="1" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
        </td>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
            <input type="number" name="total_weight[]" step="0.01" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
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
    `;
    tbody.appendChild(newRow);
}

function deleteRow(button) {
    const row = button.closest('tr');
    const tbody = document.getElementById('cargo-tbody');
    
    // Don't delete if it's the last row
    if (tbody.children.length > 1) {
        row.remove();
    } else {
        // If it's the last row, just clear the inputs
        const inputs = row.querySelectorAll('input, select');
        inputs.forEach(input => {
            if (input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            } else {
                input.value = '';
            }
        });
    }
}

// Add form submission validation
document.querySelector('form').addEventListener('submit', function(e) {
    const containerTypes = document.getElementsByName('container_type[]');
    const containerCounts = document.getElementsByName('container_count[]');
    const totalWeights = document.getElementsByName('total_weight[]');
    
    let isValid = true;
    
    for (let i = 0; i < containerTypes.length; i++) {
        if (!containerTypes[i].value || !containerCounts[i].value || !totalWeights[i].value) {
            isValid = false;
            break;
        }
    }
    
    if (!isValid) {
        e.preventDefault();
        alert('Please fill in all cargo information fields.');
    }
});
</script>
