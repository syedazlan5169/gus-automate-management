<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-6">Create New Booking</h2>
                    
                    <form method="POST" action="" class="space-y-6">
                        @csrf
                        
                        <!-- Service Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Service Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="service" value="Service Type" />
                                    <select id="service" name="service" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Select a service type</option>
                                        <option value="SOC">Shipper Owned Container (SOC)</option>
                                        <option value="COC">Carrier Owned Container (COC)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Selection -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Select Available Schedule</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vessel</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voyage</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">POL</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">POD</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ETS</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available Space</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach([
                                            [
                                                'vessel' => 'EVER GIVEN',
                                                'voyage' => 'EG123',
                                                'liner_address' => '123 Shipping Lane, Port City',
                                                'place_of_receipt' => 'Jakarta Warehouse',
                                                'pol' => 'Singapore',
                                                'pod' => 'Rotterdam',
                                                'place_of_delivery' => 'Amsterdam DC',
                                                'ets' => '2024-03-20T08:00',
                                                'eta' => '2024-04-05T16:00',
                                                'available_tonnage' => '500 TEU',
                                                'vessel_capacity' => '20,000 TEU',
                                                'transit_time' => '16 days',
                                                'route' => 'Via Suez Canal',
                                                'vessel_type' => 'Container Ship',
                                                'service_type' => 'Weekly Service',
                                                'remarks' => 'Reefer points available'
                                            ],
                                            [
                                                'vessel' => 'MAERSK SEALAND',
                                                'voyage' => 'MS456',
                                                'liner_address' => '456 Harbor Road, Sea City',
                                                'place_of_receipt' => 'Shenzhen DC',
                                                'pol' => 'Shanghai',
                                                'pod' => 'Hamburg',
                                                'place_of_delivery' => 'Berlin Terminal',
                                                'ets' => '2024-03-22T10:00',
                                                'eta' => '2024-04-08T14:00',
                                                'available_tonnage' => '300 TEU',
                                                'vessel_capacity' => '15,000 TEU',
                                                'transit_time' => '17 days',
                                                'route' => 'Direct Service',
                                                'vessel_type' => 'Container Ship',
                                                'service_type' => 'Express Service',
                                                'remarks' => 'Limited reefer points'
                                            ]
                                        ] as $schedule)
                                        <tr class="hover:bg-gray-50 cursor-pointer" 
                                            onclick="selectSchedule('{{ $schedule['vessel'] }}', '{{ $schedule['voyage'] }}', '{{ $schedule['liner_address'] }}', '{{ $schedule['place_of_receipt'] }}', '{{ $schedule['pol'] }}', '{{ $schedule['pod'] }}', '{{ $schedule['place_of_delivery'] }}', '{{ $schedule['ets'] }}', '{{ $schedule['eta'] }}')">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $schedule['vessel'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $schedule['voyage'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $schedule['pol'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $schedule['pod'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ date('Y-m-d H:i', strtotime($schedule['ets'])) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $schedule['available_tonnage'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <button type="button" 
                                                    onclick="event.stopPropagation(); showDetails({{ json_encode($schedule) }})" 
                                                    class="text-indigo-600 hover:text-indigo-900">
                                                    Details
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Modal for voyage details -->
                        <div id="detailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
                            <div class="relative top-20 mx-auto p-5 border w-[600px] shadow-lg rounded-md bg-white">
                                <div class="mt-3">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modalTitle"></h3>
                                    <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                                        <div id="modalContentLeft"></div>
                                        <div id="modalContentRight"></div>
                                    </div>
                                    <div class="mt-4">
                                        <button type="button" 
                                            onclick="closeModal()"
                                            class="mt-3 inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            function selectSchedule(vessel, voyage, linerAddress, placeOfReceipt, pol, pod, placeOfDelivery, ets, eta) {
                                // Populate Shipping Details
                                document.getElementById('vessel').value = vessel;
                                document.getElementById('voyage').value = voyage;
                                document.getElementById('liner_address').value = linerAddress;
                                
                                // Populate Route Information
                                document.getElementById('place_of_receipt').value = placeOfReceipt;
                                document.getElementById('pol').value = pol;
                                document.getElementById('pod').value = pod;
                                document.getElementById('place_of_delivery').value = placeOfDelivery;
                                
                                // Populate Schedule
                                document.getElementById('ets').value = ets;
                                document.getElementById('eta').value = eta;
                            }

                            function showDetails(schedule) {
                                const modal = document.getElementById('detailsModal');
                                const title = document.getElementById('modalTitle');
                                const contentLeft = document.getElementById('modalContentLeft');
                                const contentRight = document.getElementById('modalContentRight');
                                
                                title.textContent = `${schedule.vessel} - ${schedule.voyage}`;
                                
                                const leftContent = `
                                    <p class="font-medium mb-2">Vessel Information</p>
                                    <p>Vessel Type: ${schedule.vessel_type}</p>
                                    <p>Vessel Capacity: ${schedule.vessel_capacity}</p>
                                    <p>Available Space: ${schedule.available_tonnage}</p>
                                    <p class="font-medium mt-4 mb-2">Schedule</p>
                                    <p>ETS: ${formatDateTime(schedule.ets)}</p>
                                    <p>ETA: ${formatDateTime(schedule.eta)}</p>
                                    <p>Transit Time: ${schedule.transit_time}</p>
                                `;

                                const rightContent = `
                                    <p class="font-medium mb-2">Route Details</p>
                                    <p>Place of Receipt: ${schedule.place_of_receipt}</p>
                                    <p>Port of Loading: ${schedule.pol}</p>
                                    <p>Port of Discharge: ${schedule.pod}</p>
                                    <p>Place of Delivery: ${schedule.place_of_delivery}</p>
                                    <p class="font-medium mt-4 mb-2">Service Information</p>
                                    <p>Service Type: ${schedule.service_type}</p>
                                    <p>Route: ${schedule.route}</p>
                                    <p>Remarks: ${schedule.remarks}</p>
                                `;

                                contentLeft.innerHTML = leftContent;
                                contentRight.innerHTML = rightContent;
                                
                                modal.classList.remove('hidden');
                            }

                            function formatDateTime(dateTimeStr) {
                                return new Date(dateTimeStr).toLocaleString();
                            }

                            function closeModal() {
                                document.getElementById('detailsModal').classList.add('hidden');
                            }
                        </script>

                        <!-- Shipping Details -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Shipping Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="vessel" value="Vessel" />
                                    <x-text-input id="vessel" name="vessel" type="text" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <x-input-label for="voyage" value="Voyage" />
                                    <x-text-input id="voyage" name="voyage" type="text" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <x-input-label for="liner_address" value="Liner Address" />
                                    <x-text-input id="liner_address" name="liner_address" type="text" class="mt-1 block w-full" />
                                </div>
                            </div>
                        </div>

                        <!-- Route Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Route Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="place_of_receipt" value="Place of Receipt" />
                                    <x-text-input id="place_of_receipt" name="place_of_receipt" type="text" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <x-input-label for="pol" value="Port of Loading (POL)" />
                                    <x-text-input id="pol" name="pol" type="text" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <x-input-label for="pod" value="Port of Discharge (POD)" />
                                    <x-text-input id="pod" name="pod" type="text" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <x-input-label for="place_of_delivery" value="Place of Delivery" />
                                    <x-text-input id="place_of_delivery" name="place_of_delivery" type="text" class="mt-1 block w-full" />
                                </div>
                            </div>
                        </div>

                        <!-- Schedule -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Schedule</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="ets" value="Estimated Time of Sailing (ETS)" />
                                    <x-text-input id="ets" name="ets" type="datetime-local" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <x-input-label for="eta" value="Estimated Time of Arrival (ETA)" />
                                    <x-text-input id="eta" name="eta" type="datetime-local" class="mt-1 block w-full" />
                                </div>
                            </div>
                        </div>

                        <!-- Cargo Details -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Cargo Details</h3>
                            <div id="cargo-container">
                                <!-- Template for cargo rows -->
                                <div class="cargo-row grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                    <div>
                                        <x-input-label for="container_type[]" value="Container Type" />
                                        <select name="container_type[]" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                            <option value="">Select container type</option>
                                            <option value="20GP">20' General Purpose</option>
                                            <option value="40GP">40' General Purpose</option>
                                            <option value="40HC">40' High Cube</option>
                                            <option value="20RF">20' Reefer</option>
                                            <option value="40RF">40' Reefer</option>
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="container_count[]" value="Number of Containers" />
                                        <x-text-input name="container_count[]" type="number" min="1" class="mt-1 block w-full" />
                                    </div>
                                    <div>
                                        <x-input-label for="total_weight[]" value="Total Weight (kg)" />
                                        <x-text-input name="total_weight[]" type="number" step="0.01" class="mt-1 block w-full" />
                                    </div>
                                    <div class="flex items-end">
                                        <button type="button" class="remove-cargo-row text-red-600 hover:text-red-800 mt-1">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="button" id="add-cargo-row" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Add Container
                                </button>
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const container = document.getElementById('cargo-container');
                                const addButton = document.getElementById('add-cargo-row');

                                // Add new cargo row
                                addButton.addEventListener('click', function() {
                                    const template = container.querySelector('.cargo-row').cloneNode(true);
                                    // Clear input values
                                    template.querySelectorAll('input').forEach(input => input.value = '');
                                    template.querySelector('select').selectedIndex = 0;
                                    container.appendChild(template);
                                });

                                // Remove cargo row
                                container.addEventListener('click', function(e) {
                                    if (e.target.closest('.remove-cargo-row')) {
                                        const row = e.target.closest('.cargo-row');
                                        // Only remove if there's more than one row
                                        if (container.querySelectorAll('.cargo-row').length > 1) {
                                            row.remove();
                                        }
                                    }
                                });
                            });
                        </script>

                        <div class="flex justify-end">
                            <x-secondary-button type="button" class="mr-3">
                                Cancel
                            </x-secondary-button>
                            <x-primary-button>
                                Create Booking
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>