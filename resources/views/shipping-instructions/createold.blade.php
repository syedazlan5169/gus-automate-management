<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Create Shipping Instruction</h2>
                        <div class="text-sm text-gray-600">
                            Booking: <span class="font-semibold">{{ $booking->booking_number }}</span>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">Please check the form and try again.</span>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('shipping-instructions.store', $booking) }}" class="space-y-6">
                        @csrf

                        <!-- Shipper Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Shipper Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="shipper" value="Shipper Name" />
                                    <x-text-input id="shipper" name="shipper" type="text" 
                                        class="mt-1 block w-full" 
                                        :value="old('shipper')"
                                        required />
                                    <x-input-error :messages="$errors->get('shipper')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="contact_shipper" value="Shipper Contact" />
                                    <x-text-input id="contact_shipper" name="contact_shipper" type="text" 
                                        class="mt-1 block w-full"
                                        :value="old('contact_shipper')"
                                        required />
                                    <x-input-error :messages="$errors->get('contact_shipper')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Consignee Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Consignee Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="consignee" value="Consignee Name" />
                                    <x-text-input id="consignee" name="consignee" type="text" 
                                        class="mt-1 block w-full"
                                        :value="old('consignee')"
                                        required />
                                    <x-input-error :messages="$errors->get('consignee')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="contact_consignee" value="Consignee Contact" />
                                    <x-text-input id="contact_consignee" name="contact_consignee" type="text" 
                                        class="mt-1 block w-full"
                                        :value="old('contact_consignee')"
                                        required />
                                    <x-input-error :messages="$errors->get('contact_consignee')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Cargo Description -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Cargo Description</h3>
                            <div>
                                <x-input-label for="cargo_description" value="Cargo Description" />
                                <textarea 
                                    id="cargo_description" 
                                    name="cargo_description" 
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    rows="3"
                                    required>{{ old('cargo_description') }}</textarea>
                                <x-input-error :messages="$errors->get('cargo_description')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Container Allocation -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Container Allocation</h3>
                            
                            @if($availableCargos->isEmpty())
                                <p class="text-red-600">No containers available for allocation.</p>
                            @else
                                @foreach($availableCargos as $containerType => $cargos)
                                    <div class="mb-4 p-4 border rounded">
                                        <h4 class="font-medium mb-2">{{ $containerType }}</h4>
                                        @foreach($cargos as $cargo)
                                            <div class="space-y-4" data-cargo-index="{{ $loop->parent->index }}_{{ $loop->index }}">
                                                <input type="hidden" name="cargo_allocations[{{ $loop->parent->index }}_{{ $loop->index }}][cargo_id]" value="{{ $cargo->id }}">
                                                
                                                <div class="text-sm text-gray-600">
                                                    Available slots: {{ $cargo->container_count }} containers
                                                </div>

                                                <!-- Bulk Import Section -->
                                                <div class="space-y-4 border-b pb-4 mb-4">
                                                    <h4 class="font-medium">Bulk Import Containers</h4>
                                                    <div class="flex gap-4 items-start">
                                                        <div class="flex-1">
                                                            <textarea 
                                                                id="bulk_containers_{{ $cargo->id }}"
                                                                class="w-full h-32 border-gray-300 rounded-md"
                                                                placeholder="Paste container data here...&#10;Format: CONTAINER_NUMBER,SEAL_NUMBER&#10;Example:&#10;CONT123456,SEAL789012&#10;CONT234567,SEAL890123"
                                                            ></textarea>
                                                            <p class="text-sm text-gray-500 mt-1">One container per line, comma-separated (Container Number, Seal Number)</p>
                                                        </div>
                                                        <button type="button"
                                                            class="bulk-import-btn bg-blue-500 text-white px-4 py-2 rounded"
                                                            data-cargo-id="{{ $cargo->id }}"
                                                            data-cargo-index="{{ $loop->parent->index }}_{{ $loop->index }}">
                                                            Import Containers
                                                        </button>
                                                    </div>
                                                    
                                                    <!-- Sample file download -->
                                                    <div class="text-sm">
                                                        <a href="#" class="text-blue-600 hover:text-blue-800" onclick="downloadSampleCSV(event)">
                                                            Download Sample Format
                                                        </a>
                                                    </div>
                                                </div>

                                                <!-- Manual Entry Section -->
                                                <div>
                                                    <h4 class="font-medium mb-2">Manual Entry</h4>
                                                    <div class="container-inputs-{{ $cargo->id }} max-h-[400px] overflow-y-auto border border-gray-200 rounded-md p-2">
                                                        <!-- Dynamic container inputs will be added here -->
                                                    </div>
                                                    
                                                    <button type="button" 
                                                        class="add-container-btn bg-green-500 text-white px-3 py-1 rounded text-sm mt-2"
                                                        data-cargo-id="{{ $cargo->id }}"
                                                        data-max-containers="{{ $cargo->container_count }}">
                                                        Add Single Container
                                                    </button>
                                                </div>

                                                <!-- Container Count Display -->
                                                <div class="text-sm space-y-2 mt-4">
                                                    <div>
                                                        Containers Listed: <span id="container_count_{{ $cargo->id }}" class="font-semibold">0</span>
                                                    </div>
                                                    <div>
                                                        Remaining Slots: <span id="remaining_slots_{{ $cargo->id }}" 
                                                            class="font-semibold" 
                                                            data-max="{{ $cargo->container_count }}">{{ $cargo->container_count }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Customer Instructions -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Additional Instructions</h3>
                            <div>
                                <x-input-label for="customer_instructions" value="Special Instructions" />
                                <textarea id="customer_instructions" name="customer_instructions" 
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    rows="4">{{ old('customer_instructions') }}</textarea>
                                <x-input-error :messages="$errors->get('customer_instructions')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex justify-end gap-4">
                            <x-primary-button>
                                Create Shipping Instruction
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded');

        const addButtons = document.querySelectorAll('.add-container-btn');
        console.log('Found add buttons:', addButtons.length);

        addButtons.forEach(button => {
            button.onclick = function() {
                console.log('Button clicked');
                const cargoId = this.getAttribute('data-cargo-id');
                const maxContainers = parseInt(this.getAttribute('data-max-containers'));
                const containerInputsDiv = document.querySelector(`.container-inputs-${cargoId}`);
                const cargoIndex = this.closest('[data-cargo-index]').getAttribute('data-cargo-index');
                
                console.log('CargoId:', cargoId);
                console.log('MaxContainers:', maxContainers);
                console.log('CargoIndex:', cargoIndex);

                const currentCount = containerInputsDiv.querySelectorAll('.container-input-group').length;
                
                if (currentCount < maxContainers) {
                    const html = `
                        <div class="container-input-group flex gap-2 mb-2">
                            <div class="flex-1">
                                <input type="text" 
                                    name="cargo_allocations[${cargoIndex}][containers][${currentCount}][number]" 
                                    class="container-number w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                    placeholder="Container Number"
                                    required>
                            </div>
                            <div class="flex-1">
                                <input type="text" 
                                    name="cargo_allocations[${cargoIndex}][containers][${currentCount}][seal]" 
                                    class="seal-number w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                    placeholder="Seal Number"
                                    required>
                            </div>
                            <button type="button" 
                                class="remove-container-btn bg-red-500 text-white px-3 py-1 rounded text-sm"
                                onclick="removeContainer(this, '${cargoId}')">
                                Remove
                            </button>
                        </div>
                    `;
                    
                    containerInputsDiv.insertAdjacentHTML('beforeend', html);
                    updateContainerCount(cargoId);
                }
            };
        });

        // Global function for removing containers
        window.removeContainer = function(button, cargoId) {
            button.closest('.container-input-group').remove();
            updateContainerCount(cargoId);
        };

        function updateContainerCount(cargoId) {
            console.log('Updating count for cargo:', cargoId);
            const containerInputsDiv = document.querySelector(`.container-inputs-${cargoId}`);
            const containerCount = containerInputsDiv.querySelectorAll('.container-input-group').length;
            const maxContainers = parseInt(document.querySelector(`#remaining_slots_${cargoId}`).dataset.max);
            
            // Update display counts
            document.querySelector(`#container_count_${cargoId}`).textContent = containerCount;
            document.querySelector(`#remaining_slots_${cargoId}`).textContent = maxContainers - containerCount;
            
            // Update add button state
            const addButton = document.querySelector(`[data-cargo-id="${cargoId}"]`);
            addButton.classList.toggle('opacity-50', containerCount >= maxContainers);
            addButton.classList.toggle('cursor-not-allowed', containerCount >= maxContainers);
            
            // Update remaining slots color
            const remainingElement = document.querySelector(`#remaining_slots_${cargoId}`);
            remainingElement.classList.toggle('text-red-600', containerCount > maxContainers);
        }

        // Bulk Import functionality
        document.querySelectorAll('.bulk-import-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const cargoId = this.dataset.cargoId;
                const cargoIndex = this.dataset.cargoIndex;
                const textarea = document.querySelector(`#bulk_containers_${cargoId}`);
                const containerInputsDiv = document.querySelector(`.container-inputs-${cargoId}`);
                
                const lines = textarea.value.trim().split('\n');
                let validContainers = [];
                
                // Validate input
                for (let line of lines) {
                    const [containerNum, sealNum] = line.split(',').map(item => item.trim());
                    if (containerNum && sealNum) {
                        validContainers.push({ containerNum, sealNum });
                    }
                }
                
                // Add valid containers
                validContainers.forEach((container, index) => {
                    const currentCount = containerInputsDiv.querySelectorAll('.container-input-group').length;
                    const html = `
                        <div class="container-input-group flex gap-2 mb-2">
                            <div class="flex-1">
                                <input type="text" 
                                    name="cargo_allocations[${cargoIndex}][containers][${currentCount + index}][number]" 
                                    value="${container.containerNum}"
                                    class="container-number w-full border-gray-300 rounded-md" 
                                    required>
                            </div>
                            <div class="flex-1">
                                <input type="text" 
                                    name="cargo_allocations[${cargoIndex}][containers][${currentCount + index}][seal]" 
                                    value="${container.sealNum}"
                                    class="seal-number w-full border-gray-300 rounded-md" 
                                    required>
                            </div>
                            <button type="button" 
                                class="remove-container-btn bg-red-500 text-white px-3 py-1 rounded text-sm"
                                onclick="removeContainer(this, '${cargoId}')">
                                Remove
                            </button>
                        </div>
                    `;
                    containerInputsDiv.insertAdjacentHTML('beforeend', html);
                });
                
                updateContainerCount(cargoId);
                textarea.value = ''; // Clear textarea after import
            });
        });

        // Download sample CSV
        window.downloadSampleCSV = function(event) {
            event.preventDefault();
            const sampleData = "CONT123456,SEAL789012\nCONT234567,SEAL890123";
            const blob = new Blob([sampleData], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'sample_containers.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        };
    });
    </script>

    @push('styles')
    <style>
        /* Webkit browsers custom scrollbar */
        .container-inputs-{{ $cargo->id }} {
            scrollbar-width: thin;
            scrollbar-color: #CBD5E1 #F1F5F9;
        }
        
        .container-inputs-{{ $cargo->id }}::-webkit-scrollbar {
            width: 8px;
        }
        
        .container-inputs-{{ $cargo->id }}::-webkit-scrollbar-track {
            background: #F1F5F9;
            border-radius: 4px;
        }
        
        .container-inputs-{{ $cargo->id }}::-webkit-scrollbar-thumb {
            background-color: #CBD5E1;
            border-radius: 4px;
            border: 2px solid #F1F5F9;
        }
        
        .container-inputs-{{ $cargo->id }}::-webkit-scrollbar-thumb:hover {
            background-color: #94A3B8;
        }
    </style>
    @endpush
    @endpush
</x-app-layout> 