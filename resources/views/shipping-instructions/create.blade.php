<x-app-layout>
  <div class="mx-auto flex w-full max-w-10xl flex-col px-4 py-10 sm:px-6 lg:px-8">
    <!-- Header section -->
    <div class="max-w-xl pb-8 space-y-2">
      <!-- Breadcrumb -->
      {{ Breadcrumbs::render('booking.create-shipping-instruction', $booking) }}

      <!-- Shipping Instruction Heading -->
      <h1 id="create-booking-heading" class="text-3xl font-bold tracking-tight text-gray-900">Create Shipping
        Instruction</h1>
    </div>

    <!-- Content section with flex layout -->
    <div class="flex items-start gap-x-8">
      <main class="flex-1">
        <!-- Error Alert Section -->
        <div id="error-alert" class="mb-6 hidden">
          <div class="rounded-md bg-red-50 p-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Error</h3>
                <div class="mt-2 text-sm text-red-700">
                  <p id="error-message"></p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <form action="{{ route('shipping-instructions.store', $booking) }}" method="POST" class="space-y-6">
          @csrf
          <!-- First container section -->
          <div class="overflow-hidden rounded-lg bg-white shadow">
            <div class="px-4 py-5 sm:p-6">
              <!-- Upload Shipping Instruction -->
              <div class="space-y-4 mb-12 border-b border-gray-200 pb-8">
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-medium text-gray-700">Upload Shipping Instruction</label>
                    <a href="{{ asset('template/shipping_instruction_template.xlsx') }}" download 
                        class="text-sm text-indigo-600 hover:text-indigo-500 flex items-center">
                        <svg class="w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.75 2.75a.75.75 0 00-1.5 0v8.614L6.295 8.235a.75.75 0 10-1.09 1.03l4.25 4.5a.75.75 0 001.09 0l4.25-4.5a.75.75 0 00-1.09-1.03l-2.955 3.129V2.75z" />
                            <path d="M3.5 12.75a.75.75 0 00-1.5 0v2.5A2.75 2.75 0 004.75 18h10.5A2.75 2.75 0 0018 15.25v-2.5a.75.75 0 00-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5z" />
                        </svg>
                        Download Template
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <input type="file" id="shipping_instruction_file" name="shipping_instruction_file" 
                            accept=".xlsx,.xls,.csv"
                            class="mt-2 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100"/>
                    </div>
                    <div class="flex-none pt-6">
                        <button type="button" onclick="addShippingInstruction()"
                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Add Shipping Instruction
                        </button>
                    </div>
                </div>
              </div>
              <div class="space-y-6">
                <!-- Box Operator at the top -->
                <div class="sm:col-span-3">
                  <x-input-label for="box_operator">Box Operator</x-input-label>
                  <div class="mt-2">
                    <x-text-input type="text" name="box_operator" id="box_operator" required/>
                  </div>
                </div>

                <!-- Shipper Information -->
                <h2 class="text-base/7 font-semibold text-gray-900">Shipper Information</h2>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                  <div class="sm:col-span-3">
                    <x-input-label for="shipper">Shipper Name</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="shipper" id="shipper" required/>
                    </div>
                  </div>

                  <div class="sm:col-span-3">
                    <x-input-label for="shipper_contact">Shipper Contact</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="number" name="shipper_contact" id="shipper_contact" required/>
                    </div>
                  </div>
                </div>

                <div class="sm:col-span-full">
                  <x-input-label for="shipper_address">Shipper Address</x-input-label>
                  <div class="mt-2 border rounded-md overflow-hidden">
                      <div class="flex flex-col w-full rounded-md bg-white outline outline-1 -outline-offset-1 outline-gray-300">
                          <input type="text" name="shipper_address[line1]" id="shipper_address_line1" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" required />
                          <input type="text" name="shipper_address[line2]" id="shipper_address_line2" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" />
                          <input type="text" name="shipper_address[line3]" id="shipper_address_line3" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" />
                          <input type="text" name="shipper_address[line4]" id="shipper_address_line4" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" />
                      </div>
                  </div>
                </div>

                <!-- Consignee Information -->
                <h2 class="text-base/7 font-semibold text-gray-900">Consignee Information</h2>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                  <div class="sm:col-span-3">
                    <x-input-label for="consignee">Consignee Name</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="consignee" id="consignee" required/>
                    </div>
                  </div>

                  <div class="sm:col-span-3">
                    <x-input-label for="consignee_contact">Consignee Contact</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="number" name="consignee_contact" id="consignee_contact" required/>
                    </div>
                  </div>
                </div>

                <div class="sm:col-span-full">
                  <x-input-label for="consignee_address">Consignee Address</x-input-label>
                  <div class="mt-2 border rounded-md overflow-hidden">
                      <div class="flex flex-col w-full rounded-md bg-white outline outline-1 -outline-offset-1 outline-gray-300">
                          <input type="text" name="consignee_address[line1]" id="consignee_address_line1" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" required />
                          <input type="text" name="consignee_address[line2]" id="consignee_address_line2" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" />
                          <input type="text" name="consignee_address[line3]" id="consignee_address_line3" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" />
                          <input type="text" name="consignee_address[line4]" id="consignee_address_line4" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" />
                      </div>
                  </div>
                </div>

                <!-- Notify Party Information -->
                <h2 class="text-base/7 font-semibold text-gray-900">Notify Party Information</h2>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                  <div class="sm:col-span-3">
                    <x-input-label for="notify_party">Notify Party Name</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="notify_party" id="notify_party" required/>
                    </div>
                  </div>

                  <div class="sm:col-span-3">
                    <x-input-label for="notify_party_contact">Notify Party Contact</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="number" name="notify_party_contact" id="notify_party_contact" required/>
                    </div>
                  </div>

                  <div class="sm:col-span-full">
                    <x-input-label for="notify_party_address">Notify Party Address</x-input-label>
                    <div class="mt-2 border rounded-md overflow-hidden">
                        <div class="flex flex-col w-full rounded-md bg-white outline outline-1 -outline-offset-1 outline-gray-300">
                            <input type="text" name="notify_party_address[line1]" id="notify_party_address_line1" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" required />
                            <input type="text" name="notify_party_address[line2]" id="notify_party_address_line2" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" />
                            <input type="text" name="notify_party_address[line3]" id="notify_party_address_line3" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" />
                            <input type="text" name="notify_party_address[line4]" id="notify_party_address_line4" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" />
                        </div>
                    </div>
                  </div>
                </div>

                <!-- Cargo Details -->
                <h2 class="text-base/7 font-semibold text-gray-900">Cargo Details</h2>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-4">
                  <div class="sm:col-span-1">
                    <x-input-label for="cargo_description">Cargo Description</x-input-label>
                    <div class="mt-2">
                      <textarea name="cargo_description" id="cargo_description" rows="4" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"></textarea>
                    </div>
                  </div>

                  <div class="sm:col-span-1">
                    <x-input-label for="hs_code">HS Code</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="hs_code" id="hs_code"/>
                    </div>
                  </div>

                  <div class="sm:col-span-1">
                    <x-input-label for="gross_weight">Gross Weight</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="gross_weight" id="gross_weight" required/>
                    </div>
                  </div>

                  <div class="sm:col-span-1">
                    <x-input-label for="volume">Volume</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="volume" id="volume" required/>
                    </div>
                  </div>
                </div>
              </div>
            <!-- Container Allocation -->
            <div class="mt-10 border-b border-gray-900/10 space-y-6">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold text-gray-900">Containers Allocation</h1>
                        <div class="mt-2 space-y-1" id="allocation-info">
                            @foreach($booking->cargos as $cargo)
                                @php
                                    // Count all containers that have been allocated to ANY shipping instruction
                                    $allocatedCount = $cargo->containers()
                                        ->whereNotNull('shipping_instruction_id')
                                        ->count();
                                    $availableCount = $cargo->container_count - $allocatedCount;
                                @endphp
                                <p class="text-sm {{ $availableCount > 0 ? 'text-blue-700' : 'text-red-700' }}" 
                                   data-container-type="{{ $cargo->container_type }}" 
                                   data-cargo-id="{{ $cargo->id }}" 
                                   data-total="{{ $cargo->container_count }}">
                                    {{ $cargo->container_type }}: 
                                    <strong data-available="{{ $availableCount }}">{{ $availableCount }} of {{ $cargo->container_count }} available</strong>
                                </p>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Add Container Form -->
                <div class="mt-4 space-y-4">
                    <!-- Container sections will be populated here -->
                    <div id="container-sections" class="mt-4 space-y-4"></div>
                </div>

            </div>
          </div>

          <!-- Action Buttons -->
          <div class="overflow-hidden rounded-lg bg-white shadow">
            <div class="px-4 py-5 sm:p-6">
              <div class="space-y-6">
                <!-- Error Messages Section -->
                @if ($errors->any())
                    <div class="rounded-md bg-red-50 p-4 mb-4">
                        <div class="flex">
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    There were {{ $errors->count() }} errors with your submission
                                </h3>
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

                <!-- Success Message -->
                @if (session('success'))
                    <div class="rounded-md bg-green-50 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="size-5 text-green-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex justify-between space-x-4">
                  <div>
                    <button type="button" onclick="window.history.back()"
                      class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                      Back
                    </button>
                  </div>
                  <div class="flex space-x-4">
                    <x-primary-button type="submit"
                      class="bg-blue-700">
                      Create SI
                    </x-primary-button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </main>
    </div>
  </div>

  <!-- Warning Modal -->
  <div id="warning-modal" class="relative z-10 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
      <div class="fixed inset-0 z-10 overflow-y-auto">
          <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
              <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                  <div class="sm:flex sm:items-start">
                      <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                          <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                          </svg>
                      </div>
                      <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                          <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Warning: Exceeding Container Allocation</h3>
                          <div class="mt-2">
                              <p class="text-sm text-gray-500" id="warning-message"></p>
                          </div>
                      </div>
                  </div>
                  <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                      <button type="button" id="proceed-button" class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto">Proceed</button>
                      <button type="button" id="cancel-button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                  </div>
              </div>
          </div>
      </div>
  </div>

</x-app-layout>

<script>
// Global variable to store container type to cargo ID mapping
let containerTypeToCargoId = {};

// Function to show error message
function showError(message) {
    const errorAlert = document.getElementById('error-alert');
    const errorMessage = document.getElementById('error-message');
    
    // If message is an array, format as bulleted list
    if (Array.isArray(message)) {
        errorMessage.innerHTML = '<ul class="list-disc list-inside space-y-1">' + 
            message.map(msg => `<li>${msg}</li>`).join('') + 
            '</ul>';
    } else if (typeof message === 'string' && message.includes('\n')) {
        // If string contains newlines, convert to bulleted list
        const messages = message.split('\n').filter(msg => msg.trim());
        errorMessage.innerHTML = '<ul class="list-disc list-inside space-y-1">' + 
            messages.map(msg => `<li>${msg}</li>`).join('') + 
            '</ul>';
    } else {
        errorMessage.textContent = message;
    }
    
    errorAlert.classList.remove('hidden');
    
    // Scroll to the error message
    errorAlert.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Function to clear error message
function clearError() {
    const errorAlert = document.getElementById('error-alert');
    if (errorAlert) {
        errorAlert.classList.add('hidden');
    }
}

function updateContainerCounts(containerType, change) {
    // Update dropdown option
    const option = document.querySelector(`option[value="${containerType}"]`);
    if (option) {
        const available = parseInt(option.dataset.available) - change;
        option.dataset.available = available;
        option.textContent = `${option.textContent.split('(')[0].trim()} (${available} available)`;
        
        // Remove option if no more containers available
        if (available <= 0) {
            option.remove();
        }
    }

    // Update allocation info
    const allocationInfo = document.querySelector(`p[data-container-type="${containerType}"]`);
    if (allocationInfo) {
        const strong = allocationInfo.querySelector('strong');
        const total = parseInt(allocationInfo.dataset.total);
        const initialAvailable = parseInt(strong.dataset.available); // Get the initial available count
        const currentCount = document.querySelector(`#container-group-${containerType}`).children.length;
        
        // Calculate available slots and extra containers
        const available = Math.max(0, initialAvailable - currentCount);
        const extra = Math.max(0, currentCount - initialAvailable);
        
        // Update the text based on whether we have extra containers
        if (extra > 0) {
            strong.textContent = `0 of ${total} available (${extra} extra in list)`;
            allocationInfo.className = 'text-sm text-red-700';
        } else {
            strong.textContent = `${available} of ${total} available`;
            allocationInfo.className = `text-sm ${available > 0 ? 'text-blue-700' : 'text-red-700'}`;
        }
    }
}

function removeContainer(button) {
    const containerItem = button.closest('.flex');
    if (containerItem) {
        const containerType = containerItem.querySelector('input[type="hidden"]').value;
        const cargoId = containerItem.querySelector('input[type="hidden"]').name.match(/containers\[(\d+)\]/)[1];
        containerItem.remove();
        
        // Update the visual count
        updateContainerCount(containerType);
        
        // Update the allocation info counter
        const allocationInfo = document.querySelector(`p[data-container-type="${containerType}"]`);
        if (allocationInfo) {
            const strong = allocationInfo.querySelector('strong');
            const total = parseInt(allocationInfo.dataset.total);
            const initialAvailable = parseInt(strong.dataset.available); // Get the initial available count
            const currentCount = document.querySelector(`#container-group-${containerType}`).children.length;
            
            // Calculate available slots and extra containers
            const available = Math.max(0, initialAvailable - currentCount);
            const extra = Math.max(0, currentCount - initialAvailable);
            
            // Update the text based on whether we have extra containers
            if (extra > 0) {
                strong.textContent = `0 of ${total} available (${extra} extra in list)`;
                allocationInfo.className = 'text-sm text-red-700';
            } else {
                strong.textContent = `${available} of ${total} available`;
                allocationInfo.className = `text-sm ${available > 0 ? 'text-blue-700' : 'text-red-700'}`;
            }
        }
        
        // Check if the container group is empty
        const containerSection = document.querySelector(`div[data-container-type="${containerType}"]`);
        const containerGroup = containerSection.querySelector(`#container-group-${containerType}`);
        
        if (containerGroup && containerGroup.children.length === 0) {
            containerSection.remove();
        }

        // Reindex the remaining containers for this cargo type
        const remainingContainers = containerGroup.querySelectorAll('.flex');
        remainingContainers.forEach((container, index) => {
            const inputs = container.querySelectorAll('input');
            inputs.forEach(input => {
                const name = input.name;
                if (name.includes('containers[')) {
                    input.name = name.replace(/containers\[\d+\]\[\d+\]/, `containers[${cargoId}][${index}]`);
                }
            });
        });
    }
}

function addShippingInstruction() {
    let fileInput = document.getElementById("shipping_instruction_file");

    // Check if we have a file to process
    if (fileInput.files.length > 0) {
        // Clear all the input fields and container sections
        document.querySelectorAll('input').forEach(input => {
            // Don't clear the file input or CSRF token
            if (input !== fileInput && input.name !== '_token') {
                input.value = '';
            }
        });
        
        // Clear container sections properly
        const containerSections = document.getElementById('container-sections');
        if (containerSections) {
            containerSections.innerHTML = ''; // Clear all container sections
        }
        
        // Process file upload
        handleFileUpload();
    } else {
        showError("Please select a file to upload");
    }
}

function handleFileUpload() {
    // Clear any previous error messages
    clearError();
    
    const fileInput = document.getElementById("shipping_instruction_file");
    const file = fileInput.files[0];
    const formData = new FormData();
    formData.append('file', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    fetch('/shipping-instructions/parse-shipping-instruction', {
        method: 'POST',
        body: formData
    })
    .then(async response => {
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            throw new Error('Server returned non-JSON response. Please check the server logs.');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Track invalid container number message to show later with other errors
            let invalidContainerNumberMessage = null;
            if (data.warnings && data.warnings.invalid_containers && data.warnings.invalid_containers.length > 0) {
                // Remove "File processed successfully" prefix from the message
                invalidContainerNumberMessage = data.message.replace(/^File processed successfully\.?\s*/i, '');
            }
            
            // Populate form fields with shipping data
            if (data.shippingData) {
                // Box Operator
                if (data.shippingData.box_operator) {
                    document.getElementById('box_operator').value = data.shippingData.box_operator;
                }
                
                // Shipper Information
                if (data.shippingData.shipper) {
                    document.getElementById('shipper').value = data.shippingData.shipper;
                }
                if (data.shippingData.shipper_contact) {
                    document.getElementById('shipper_contact').value = data.shippingData.shipper_contact;
                }
                if (data.shippingData.shipper_address_line1) {
                    document.getElementById('shipper_address_line1').value = data.shippingData.shipper_address_line1;
                }
                if (data.shippingData.shipper_address_line2) {
                    document.getElementById('shipper_address_line2').value = data.shippingData.shipper_address_line2;
                }
                if (data.shippingData.shipper_address_line3) {
                    document.getElementById('shipper_address_line3').value = data.shippingData.shipper_address_line3;
                }
                if (data.shippingData.shipper_address_line4) {
                    document.getElementById('shipper_address_line4').value = data.shippingData.shipper_address_line4;
                }
               
                // Consignee Information
                if (data.shippingData.consignee) {
                    document.getElementById('consignee').value = data.shippingData.consignee;
                }
                if (data.shippingData.consignee_contact) {
                    document.getElementById('consignee_contact').value = data.shippingData.consignee_contact;
                }
                if (data.shippingData.consignee_address_line1) {
                    document.getElementById('consignee_address_line1').value = data.shippingData.consignee_address_line1;
                }
                if (data.shippingData.consignee_address_line2) {
                    document.getElementById('consignee_address_line2').value = data.shippingData.consignee_address_line2;
                }
                if (data.shippingData.consignee_address_line3) {
                    document.getElementById('consignee_address_line3').value = data.shippingData.consignee_address_line3;
                }
                if (data.shippingData.consignee_address_line4) {
                    document.getElementById('consignee_address_line4').value = data.shippingData.consignee_address_line4;
                }
                
                // Notify Party Information
                if (data.shippingData.notify_party) {
                    document.getElementById('notify_party').value = data.shippingData.notify_party;
                }
                if (data.shippingData.notify_party_contact) {
                    document.getElementById('notify_party_contact').value = data.shippingData.notify_party_contact;
                }
                if (data.shippingData.notify_party_address_line1) {
                    document.getElementById('notify_party_address_line1').value = data.shippingData.notify_party_address_line1;
                }
                if (data.shippingData.notify_party_address_line2) {
                    document.getElementById('notify_party_address_line2').value = data.shippingData.notify_party_address_line2;
                }
                if (data.shippingData.notify_party_address_line3) {
                    document.getElementById('notify_party_address_line3').value = data.shippingData.notify_party_address_line3;
                }
                if (data.shippingData.notify_party_address_line4) {
                    document.getElementById('notify_party_address_line4').value = data.shippingData.notify_party_address_line4;
                }
                
                // Cargo Details
                if (data.shippingData.cargo_description) {
                    document.getElementById('cargo_description').value = data.shippingData.cargo_description;
                }
                if (data.shippingData.hs_code) {
                    document.getElementById('hs_code').value = data.shippingData.hs_code;
                }
                if (data.shippingData.gross_weight) {
                    document.getElementById('gross_weight').value = formatNumber(data.shippingData.gross_weight);
                }
                if (data.shippingData.volume) {
                    document.getElementById('volume').value = formatNumber(data.shippingData.volume);
                }
            }
            
            // Process the containers
            if (data.containers && data.containers.length > 0) {
                //console.log("Processing containers:", data.containers);
                
                // Create a mapping of container type codes to cargo IDs
                containerTypeToCargoId = {};
                document.querySelectorAll('#allocation-info p').forEach(p => {
                    const containerType = p.getAttribute('data-container-type');
                    const cargoId = p.getAttribute('data-cargo-id');
                    containerTypeToCargoId[containerType] = cargoId;
                });
                
                //console.log("Container type to cargo ID mapping:", containerTypeToCargoId);
                
                // Track containers with missing or invalid container types
                const containersWithMissingType = [];
                const containersWithInvalidType = [];
                
                // Map the container data to the format expected by processContainers
                const formattedContainers = data.containers.map(container => {
                    // Get the container type from the Excel file
                    const containerType = container.type ? container.type.trim() : '';
                    
                    // Check if container type is missing or empty
                    if (!containerType || containerType === '') {
                        containersWithMissingType.push(container.number);
                        return null; // Mark for filtering
                    }
                    
                    // Find the corresponding cargo ID
                    const cargoId = containerTypeToCargoId[containerType];
                    
                    if (!cargoId) {
                        containersWithInvalidType.push({
                            number: container.number,
                            type: containerType
                        });
                        console.warn(`No matching cargo ID found for container type: ${containerType}`);
                        return null; // Mark for filtering - invalid container type
                    }
                    
                    return {
                        container: container.number,
                        seal: container.seal,
                        type: containerType,
                        is_invalid: container.is_invalid || false,
                        validation_error: container.validation_error || null
                    };
                }).filter(container => container !== null); // Filter out containers with missing or invalid types
                
                // Show warnings/errors for containers with missing or invalid types
                let warningMessages = [];
                
                // Add invalid container number message if present
                if (invalidContainerNumberMessage) {
                    warningMessages.push(invalidContainerNumberMessage);
                }
                
                if (containersWithMissingType.length > 0) {
                    warningMessages.push(
                        `${containersWithMissingType.length} container(s) skipped due to missing container type. ` +
                        `Please specify a container type for all containers in the Excel file.`
                    );
                }
                
                if (containersWithInvalidType.length > 0) {
                    warningMessages.push(
                        `${containersWithInvalidType.length} container(s) skipped due to invalid container type. ` +
                        `Please ensure the container type matches one of the available types in the allocation.`
                    );
                }
                
                if (warningMessages.length > 0) {
                    showError(warningMessages);
                } else {
                    // Clear any previous error messages if processing is successful
                    clearError();
                }
                
                // Only process containers if we have valid ones
                if (formattedContainers.length > 0) {
                    processContainers(formattedContainers);
                } else {
                    showError("No valid containers found. All containers were skipped due to missing or invalid container types.");
                }
            } else {
                console.log("No containers found in the uploaded file");
                showError("No containers found in the uploaded file");
            }
            
            // Clear the file input
            fileInput.value = '';
        } else {
            showError(data.message || 'Error parsing file');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Error uploading file: ' + error.message);
    });
}

function processTextareaInput() {
    let textarea = document.getElementById("container_list");
    let lines = textarea.value.trim().split("\n");
    let containers = [];

    if (!textarea.value.trim()) {
        showError("Please enter container details or select a file to upload");
        return;
    }

    lines.forEach(line => {
        let parts = line.split(",");
        if (parts.length === 2) {
            let containerNumber = parts[0].trim();
            let sealNumber = parts[1].trim();
            if (containerNumber && sealNumber) {
                containers.push({ container: containerNumber, seal: sealNumber });
            }
        }
    });

    if (containers.length === 0) {
        showError("Invalid format! Please enter containers in the format: CONTAINER,SEAL");
        return;
    }

    processContainers(containers);
    // Clear textarea after successful processing
    textarea.value = "";
}

function processContainers(containers) {
    // Group containers by type
    const containersByType = {};
    
    containers.forEach(container => {
        // Use the container type from the data or default to 20GP
        let containerType = container.type || '20GP';
        
        // Add to the appropriate group
        if (!containersByType[containerType]) {
            containersByType[containerType] = [];
        }
        containersByType[containerType].push(container);
    });
    
    // Process each container type group
    for (const containerType in containersByType) {
        const containerList = containersByType[containerType];
        
        // Find the cargo ID for this container type
        const cargoId = containerTypeToCargoId[containerType];
        
        if (!cargoId) {
            console.warn(`No matching cargo ID found for container type: ${containerType}`);
            continue; // Skip this container type if no matching cargo ID is found
        }
        
        // Check if a section for this container type already exists
        let existingSection = document.querySelector(`div[data-container-type="${containerType}"]`);
        let containerGroup;
        
        if (!existingSection) {
            // Create new section
            existingSection = document.createElement("div");
            existingSection.setAttribute("data-container-type", containerType);
            existingSection.classList.add("border", "rounded-md", "mt-4", "bg-white", "shadow");
            
            // Create container group div
            containerGroup = document.createElement("div");
            containerGroup.id = `container-group-${containerType}`;
            containerGroup.classList.add("space-y-3", "max-h-[400px]", "overflow-y-auto");
            
            // Set up the section HTML structure
            existingSection.innerHTML = `
                <div class="px-4 py-3 border-b">
                    <h3 class="text-base font-semibold text-gray-900">
                        ${containerType}
                        <span class="ml-2 text-sm font-normal text-gray-600" id="count-${containerType}">Total: 0</span>
                    </h3>
                </div>
                <div class="px-4 py-3"></div>
            `;
            
            // Append the container group to the inner div
            existingSection.querySelector('div:last-child').appendChild(containerGroup);
            
            // Add the section to the main container sections area
            document.getElementById("container-sections").appendChild(existingSection);
        } else {
            // Get existing container group
            containerGroup = existingSection.querySelector(`#container-group-${containerType}`);
        }
        
        // Now add the containers
        containerList.forEach((entry, index) => {
            let div = document.createElement("div");
            div.classList.add("flex", "items-center", "gap-x-4", "w-full");
            
            // Determine if this container is invalid
            const isInvalid = entry.is_invalid || false;
            const validationError = entry.validation_error || '';
            const containerInputClass = isInvalid 
                ? 'block w-full rounded-md bg-red-50 px-3 py-1.5 text-base text-gray-900 outline outline-2 -outline-offset-1 outline-red-500 sm:text-sm/6'
                : 'block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 sm:text-sm/6';
            
            div.innerHTML = `
                <input type="hidden" name="containers[${cargoId}][${index}][container_type]" value="${containerType}">
                <input type="text" name="containers[${cargoId}][${index}][container_number]" value="${entry.container}" 
                    class="${containerInputClass}"
                    ${isInvalid ? 'title="' + validationError + '"' : ''}>
                <input type="text" name="containers[${cargoId}][${index}][seal_number]" value="${entry.seal}" 
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 sm:text-sm/6">
                <button type="button" class="text-gray-400 hover:text-red-600" onclick="removeContainer(this)">
                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                    </svg>
                </button>
            `;
            
            containerGroup.appendChild(div);
        });
        
        // Update counts
        updateContainerCounts(containerType, containerList.length);
        updateContainerCount(containerType);
    }
}

function updateContainerCount(containerType) {
    const containerGroup = document.getElementById(`container-group-${containerType}`);
    const countElement = document.getElementById(`count-${containerType}`);
    if (containerGroup && countElement) {
        const count = containerGroup.children.length;
        countElement.textContent = `Total: ${count}`;
    }
}

// Add this helper function for text selection in querySelector
document.querySelector = ((function(original) {
    return function(selector) {
        if (selector.includes(':contains')) {
            const [tag, text] = selector.split(':contains(');
            const cleanText = text.slice(0, -1);
            const elements = document.getElementsByTagName(tag || '*');
            
            for (const element of elements) {
                if (element.textContent.includes(cleanText)) {
                    return element;
                }
            }
            return null;
        }
        return original.apply(this, arguments);
    };
})(document.querySelector));

// Format number with 2 decimal places and comma separator
function formatNumber(value) {
    if (!value || value.toString().trim() === '') return '';
    
    // Remove all non-numeric characters except decimal point
    let numStr = value.toString().replace(/[^\d.]/g, '');
    
    // Handle multiple decimal points - keep only the first one
    const parts = numStr.split('.');
    if (parts.length > 2) {
        numStr = parts[0] + '.' + parts.slice(1).join('');
    }
    
    // Handle trailing decimal point
    if (numStr.endsWith('.')) {
        numStr = numStr.slice(0, -1);
    }
    
    // Parse to number
    const num = parseFloat(numStr);
    if (isNaN(num)) return '';
    
    // Format with 2 decimal places and comma separator
    return num.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Remove formatting (commas) from number string
function removeFormatting(value) {
    if (!value) return '';
    return value.toString().replace(/,/g, '');
}

// Initialize number formatting for gross_weight and volume inputs
document.addEventListener('DOMContentLoaded', function() {
    const grossWeightInput = document.getElementById('gross_weight');
    const volumeInput = document.getElementById('volume');
    
    // Format on blur and paste
    if (grossWeightInput) {
        grossWeightInput.addEventListener('blur', function(e) {
            e.target.value = formatNumber(e.target.value);
        });
        
        grossWeightInput.addEventListener('paste', function(e) {
            setTimeout(() => {
                e.target.value = formatNumber(e.target.value);
            }, 0);
        });
    }
    
    if (volumeInput) {
        volumeInput.addEventListener('blur', function(e) {
            e.target.value = formatNumber(e.target.value);
        });
        
        volumeInput.addEventListener('paste', function(e) {
            setTimeout(() => {
                e.target.value = formatNumber(e.target.value);
            }, 0);
        });
    }
});

// Modify the form submission validation
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Basic validation 
    const requiredFields = [
        'box_operator',
        'shipper',
        'shipper_contact',
        'shipper_address_line1',
        'consignee',
        'consignee_contact',
        'consignee_address_line1',
        'notify_party',
        'notify_party_contact',
        'notify_party_address_line1',
        'cargo_description',
        'gross_weight',
    ];

    let isValid = true;
    let missingFields = [];
    
    requiredFields.forEach(field => {
        const element = document.getElementById(field);
        if (!element.value.trim()) {
            isValid = false;
            element.classList.add('outline-red-500');
            missingFields.push(field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()));
        } else {
            element.classList.remove('outline-red-500');
        }
    });

    // Check if any containers have been added
    const containerSections = document.getElementById('container-sections');
    if (!containerSections.children.length) {
        isValid = false;
        showError('Please add at least one container');
        return;
    }

    if (isValid) {
        // Remove formatting from gross_weight and volume before submission
        const grossWeightInput = document.getElementById('gross_weight');
        const volumeInput = document.getElementById('volume');
        if (grossWeightInput) {
            grossWeightInput.value = removeFormatting(grossWeightInput.value);
        }
        if (volumeInput) {
            volumeInput.value = removeFormatting(volumeInput.value);
        }
        
        // Check for exceeding containers
        const exceedingContainers = [];
        document.querySelectorAll('#allocation-info p').forEach(p => {
            const containerType = p.getAttribute('data-container-type');
            const cargoId = p.getAttribute('data-cargo-id');
            const total = parseInt(p.getAttribute('data-total'));
            const available = parseInt(p.querySelector('strong').getAttribute('data-available'));
            
            const containerGroup = document.querySelector(`#container-group-${containerType}`);
            if (containerGroup) {
                const currentCount = containerGroup.children.length;
                if (currentCount > available) {
                    exceedingContainers.push({
                        type: containerType,
                        cargoId: cargoId,
                        current: currentCount,
                        available: available,
                        exceeding: currentCount - available
                    });
                }
            }
        });

        if (exceedingContainers.length > 0) {
            // Show warning modal
            const warningModal = document.getElementById('warning-modal');
            const warningMessage = document.getElementById('warning-message');
            const proceedButton = document.getElementById('proceed-button');
            const cancelButton = document.getElementById('cancel-button');

            // Build warning message
            let message = 'The following container types exceed the current allocation:<br><br>';
            exceedingContainers.forEach(container => {
                message += `${container.type}: ${container.current} containers (${container.exceeding} exceeding)<br>`;
            });
            message += '<br>Do you want to proceed? This will automatically update the cargo allocation.';
            warningMessage.innerHTML = message;

            // Show modal
            warningModal.classList.remove('hidden');

            // Handle proceed button
            proceedButton.onclick = () => {
                warningModal.classList.add('hidden');
                // Add hidden input for exceeding containers
                const exceedingInput = document.createElement('input');
                exceedingInput.type = 'hidden';
                exceedingInput.name = 'exceeding_containers';
                exceedingInput.value = JSON.stringify(exceedingContainers);
                this.appendChild(exceedingInput);
                // Submit the form
                this.submit();
            };

            // Handle cancel button
            cancelButton.onclick = () => {
                warningModal.classList.add('hidden');
            };
        } else {
            // No exceeding containers, submit normally
            this.submit();
        }
    } else {
        showError(`Please fill in all required fields: ${missingFields.join(', ')}`);
    }
});

// Add this at the end of your script section
// Check for error message in session on page load
document.addEventListener('DOMContentLoaded', function() {
    @if(session('error'))
        showError('{{ session('error') }}');
    @endif
});
</script>

