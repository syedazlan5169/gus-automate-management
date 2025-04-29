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
              <svg class="size-5 shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                data-slot="icon">
                <path fill-rule="evenodd"
                  d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                  clip-rule="evenodd" />
              </svg>
              <a href="#" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Projects</a>
            </div>
          </li>
          <li>
            <div class="flex items-center">
              <svg class="size-5 shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                data-slot="icon">
                <path fill-rule="evenodd"
                  d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                  clip-rule="evenodd" />
              </svg>
              <a href="#" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700" aria-current="page">Project
                Nero</a>
            </div>
          </li>
        </ol>
      </nav>

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
                      <x-text-input type="text" name="shipper_contact" id="shipper_contact" required/>
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
                      <x-text-input type="text" name="consignee_contact" id="consignee_contact" required/>
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
                      <x-text-input type="text" name="notify_party_contact" id="notify_party_contact" required/>
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
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                  <div class="sm:col-span-3">
                    <x-input-label for="cargo_description">Cargo Description</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="cargo_description" id="cargo_description" required/>
                    </div>
                  </div>

                  <div class="sm:col-span-3">
                    <x-input-label for="hs_code">HS Code</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="hs_code" id="hs_code" required/>
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

</x-app-layout>

<script>
// Global variable to store container type to cargo ID mapping
let containerTypeToCargoId = {};

// Function to show error message
function showError(message) {
    const errorAlert = document.getElementById('error-alert');
    const errorMessage = document.getElementById('error-message');
    
    errorMessage.textContent = message;
    errorAlert.classList.remove('hidden');
    
    // Scroll to the error message
    errorAlert.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function removeContainer(button) {
    const containerItem = button.closest('.flex');
    if (containerItem) {
        const containerType = containerItem.querySelector('input[type="hidden"]').value;
        containerItem.remove();
        
        // Update counts
        updateContainerCounts(containerType, -1);
        updateContainerCount(containerType);
        
        // Check if the container group is empty
        const containerSection = document.querySelector(`div[data-container-type="${containerType}"]`);
        const containerGroup = containerSection.querySelector(`#container-group-${containerType}`);
        
        if (containerGroup && containerGroup.children.length === 0) {
            containerSection.remove();
        }
    }
}

function updateContainerCounts(containerType, change) {
    // Update dropdown option
    const option = document.querySelector(`option[value="${containerType}"]`);
    if (option) {
        const available = parseInt(option.dataset.available) - change;
        option.dataset.available = available;
        option.textContent = `${option.textContent.split('(')[0].trim()} (${available} available)`;
        
        // If containers become available again, make sure the option is in the dropdown
        if (available > 0 && !option.parentElement) {
            const containerTypeSelect = document.getElementById('container_type');
            let added = false;
            // Add option in the correct position (maintain alphabetical order)
            for (let i = 0; i < containerTypeSelect.options.length; i++) {
                if (containerTypeSelect.options[i].text > option.text) {
                    containerTypeSelect.add(option, i);
                    added = true;
                    break;
                }
            }
            if (!added) {
                containerTypeSelect.add(option);
            }
        }
    }

    // Update allocation info
    const allocationInfo = document.querySelector(`p[data-container-type="${containerType}"]`);
    if (allocationInfo) {
        const strong = allocationInfo.querySelector('strong');
        const total = allocationInfo.dataset.total;
        const available = parseInt(strong.dataset.available) - change;
        strong.dataset.available = available;
        strong.textContent = `${available} of ${total} available`;
        
        // Update color based on availability
        allocationInfo.className = `text-sm ${available > 0 ? 'text-blue-700' : 'text-red-700'}`;
    } else {
        // If the allocation info doesn't exist for this container type, we need to create it
        // This happens when we're adding a container type that wasn't in the original booking
        const allocationInfoContainer = document.getElementById('allocation-info');
        if (allocationInfoContainer) {
            const newAllocationInfo = document.createElement('p');
            newAllocationInfo.setAttribute('data-container-type', containerType);
            newAllocationInfo.setAttribute('data-total', '0'); // We don't know the total for this type
            newAllocationInfo.className = 'text-sm text-blue-700';
            newAllocationInfo.innerHTML = `${containerType}: <strong data-available="0">0 of 0 available</strong>`;
            allocationInfoContainer.appendChild(newAllocationInfo);
        }
    }
}

function addShippingInstruction() {
    let fileInput = document.getElementById("shipping_instruction_file");
    
    // Check if we have a file to process
    if (fileInput.files.length > 0) {
        // Process file upload
        handleFileUpload();
    } else {
        showError("Please select a file to upload");
    }
}

function handleFileUpload() {
    const fileInput = document.getElementById("shipping_instruction_file");
    const file = fileInput.files[0];
    const formData = new FormData();
    formData.append('file', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    fetch('/shipping-instructions/parse-shipping-instruction', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
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
            }
            
            // Process the containers
            if (data.containers && data.containers.length > 0) {
                console.log("Processing containers:", data.containers);
                
                // Create a mapping of container type codes to cargo IDs
                containerTypeToCargoId = {};
                document.querySelectorAll('#allocation-info p').forEach(p => {
                    const containerType = p.getAttribute('data-container-type');
                    const cargoId = p.getAttribute('data-cargo-id');
                    containerTypeToCargoId[containerType] = cargoId;
                });
                
                console.log("Container type to cargo ID mapping:", containerTypeToCargoId);
                
                // Map the container data to the format expected by processContainers
                const formattedContainers = data.containers.map(container => {
                    // Get the container type from the Excel file
                    const containerType = container.type;
                    
                    // Find the corresponding cargo ID
                    const cargoId = containerTypeToCargoId[containerType];
                    
                    if (!cargoId) {
                        console.warn(`No matching cargo ID found for container type: ${containerType}`);
                    }
                    
                    return {
                        container: container.number,
                        seal: container.seal,
                        type: containerType 
                    };
                });
                
                processContainers(formattedContainers);
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
            
            div.innerHTML = `
                <input type="hidden" name="containers[${cargoId}][${index}][container_type]" value="${containerType}">
                <input type="text" name="containers[${cargoId}][${index}][container_number]" value="${entry.container}" 
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 sm:text-sm/6">
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
        'hs_code'
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
        // Submit the form
        this.submit();
    } else {
        showError(`Please fill in all required fields: ${missingFields.join(', ')}`);
    }
});
</script>

