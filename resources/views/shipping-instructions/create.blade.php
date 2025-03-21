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
      <!-- For admin, View Shipping Instruction, for user, Create Shipping Instruction -->
    </div>

    <!-- Content section with flex layout -->
    <div class="flex items-start gap-x-8">
      <main class="flex-1">
        <form action="{{ route('shipping-instructions.store', $booking) }}" method="POST" class="space-y-6">
          @csrf
          <!-- First container section -->
          <div class="overflow-hidden rounded-lg bg-white shadow">
            <div class="px-4 py-5 sm:p-6">
              <div class="space-y-6">
                <!-- Box Operator at the top -->
                <div class="sm:col-span-3">
                  <x-input-label for="box_operator">Box Operator</x-input-label>
                  <div class="mt-2">
                    <select id="box_operator" name="box_operator" 
                      class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                      <option value="">Select Box Operator</option>
                      <option value="MAERSK">MAERSK</option>
                      <option value="CMA CGM">CMA CGM</option>
                      <option value="HAPPAG LLOYD">HAPPAG LLOYD</option>
                    </select>
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
                    <x-input-label for="contact_shipper">Shipper Contact</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="contact_shipper" id="contact_shipper" required/>
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
                    <x-input-label for="contact_consignee">Consignee Contact</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="contact_consignee" id="contact_consignee" required/>
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
                    <div class="mt-2">
                      <textarea rows="3" name="notify_party_address" id="notify_party_address"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"></textarea>
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
            </div>
          </div>

          <div class="overflow-hidden rounded-lg bg-white shadow">
            <div class="px-4 py-5 sm:p-6">
              <div class="space-y-6">
            <!-- Container Allocation -->
            <div class="border-b border-gray-900/10 space-y-6">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold text-gray-900">Containers Allocation</h1>
                        <div class="mt-2 space-y-1" id="allocation-info">
                            @foreach($booking->cargos as $cargo)
                                @php
                                    $allocatedCount = $cargo->containers->whereNotNull('shipping_instruction_id')->count();
                                    $availableCount = $cargo->container_count - $allocatedCount;
                                @endphp
                                <p class="text-sm {{ $availableCount > 0 ? 'text-blue-700' : 'text-red-700' }}" 
                                   data-container-type="{{ $cargo->id }}" 
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
                    <div class="sm:col-span-2">
                        <label for="container_type" class="block text-sm font-medium text-gray-900">Container Type</label>
                        <select id="container_type" name="container_type" required
                            class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option value="">Select Container Type</option>
                            @foreach($booking->cargos as $cargo)
                                @php
                                    $availableCount = $cargo->container_count - $cargo->containers->whereNotNull('shipping_instruction_id')->count();
                                @endphp
                                @if($availableCount > 0)
                                    <option value="{{ $cargo->id }}" data-available="{{ $availableCount }}">
                                        {{ $cargo->container_type }} ({{ $availableCount }} available)
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="container_list" class="block text-sm font-medium text-gray-900">
                            Container List (Format: CONTAINER,SEAL - One per line)
                        </label>
                        <textarea id="container_list" name="container_list" rows="4"
                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            placeholder="TEMU0192292,SEAL001"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" onclick="addContainers()"
                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Add Containers
                        </button>
                    </div>
                </div>

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

      <aside class="sticky top-8 hidden w-96 shrink-0 xl:block">
        <!-- Right column area -->
        <div class="overflow-hidden rounded-lg bg-white shadow">
          <div class="px-4 py-5 sm:p-6">
            <nav aria-label="Progress">
              <ol role="list" class="overflow-hidden">
                <li class="relative pb-10">
                  <div class="absolute left-4 top-4 -ml-px mt-0.5 h-full w-0.5 bg-indigo-600" aria-hidden="true"></div>
                  <!-- Complete Step -->
                  <a href="#" class="group relative flex items-start">
                    <span class="flex h-9 items-center">
                      <span
                        class="relative z-10 flex size-8 items-center justify-center rounded-full bg-indigo-600 group-hover:bg-indigo-800">
                        <svg class="size-5 text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                          data-slot="icon">
                          <path fill-rule="evenodd"
                            d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z"
                            clip-rule="evenodd" />
                        </svg>
                      </span>
                    </span>
                    <span class="ml-4 flex min-w-0 flex-col">
                      <span class="text-sm font-medium">Shipper Information</span>
                      <span class="text-sm text-gray-500">Provide shipper and consingnee details.</span>
                    </span>
                  </a>
                </li>
                <li class="relative">
                  <!-- Upcoming Step -->
                  <a href="#" class="group relative flex items-start">
                    <span class="flex h-9 items-center" aria-hidden="true">
                      <span
                        class="relative z-10 flex size-8 items-center justify-center rounded-full border-2 border-gray-300 bg-white group-hover:border-gray-400">
                        <span class="size-2.5 rounded-full bg-transparent group-hover:bg-gray-300"></span>
                      </span>
                    </span>
                    <span class="ml-4 flex min-w-0 flex-col">
                      <span class="text-sm font-medium text-indigo-600">Container Details</span>
                      <span class="text-sm text-gray-500">Provide container details (Box Operator, Container Size, HS
                        Code, Notify Party Address).</span>
                    </span>
                  </a>
                </li>

                <!-- class for current step. please hide once applied. -->
                <li class="relative pb-10">
                  <div class="absolute left-4 top-4 -ml-px mt-0.5 h-full w-0.5 bg-gray-300" aria-hidden="true"></div>
                  <!-- Current Step -->
                  <a href="#" class="group relative flex items-start" aria-current="step">
                    <span class="flex h-9 items-center" aria-hidden="true">
                      <span
                        class="relative z-10 flex size-8 items-center justify-center rounded-full border-2 border-indigo-600 bg-white">
                        <span class="size-2.5 rounded-full bg-indigo-600"></span>
                      </span>
                    </span>
                    <span class="ml-4 flex min-w-0 flex-col"></span>
                  </a>
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </aside>
    </div>
  </div>

  <!-- Modal -->
  <div id="si-upload-modal" class="hidden relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true">
    </div>
    <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
      <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div
          class="z-50 relative w-full transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-5xl sm:p-6">
          <div>
            <div class="mt-3 sm:mt-5">
              <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Upload Container
                Details</h3>
              <div class="mt-4">
                <form class="space-y-4" id="upload-form" enctype="multipart/form-data">
                  @csrf
                  <div class="space-y-4">
                    <!-- Container Type Selection -->
                    <div class="sm:col-span-2">
                      <label for="container_type" class="block text-sm/6 font-medium text-gray-900">Container
                        Type</label>
                      <div class="mt-2">
                        <select id="container_type" name="container_type" required
                          class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                          <option value="">Select Container Type</option>
                          @foreach($booking->cargos as $cargo)
                            @php
                              $availableCount = $cargo->container_count - $cargo->containers->whereNotNull('shipping_instruction_id')->count();
                            @endphp
                            @if($availableCount > 0)
                              <option value="{{ $cargo->id }}" data-available="{{ $availableCount }}">
                                {{ $cargo->container_type }} ({{ $availableCount }} available)
                              </option>
                            @endif
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <!-- Quantity -->
                    <div class="sm:col-span-2">
                      <label for="quantity" class="block text-sm/6 font-medium text-gray-900">Quantity</label>
                      <div class="mt-2">
                        <input type="number" id="quantity" name="quantity" min="1" required
                          class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                      </div>
                    </div>

                    <!-- File Upload -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Upload Container List</label>
                      <div class="mt-1 flex items-center justify-center w-full">
                        <label class="w-full flex flex-col items-center px-4 py-6 bg-white rounded-lg border-2 border-dashed border-gray-300 cursor-pointer hover:border-indigo-600">
                          <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                          </svg>
                          <span class="mt-2 text-sm text-gray-600">Click to upload or drag and drop</span>
                          <span class="mt-1 text-xs text-gray-500">Excel or CSV file</span>
                          <input type="file" class="hidden" id="container_file" name="container_file" accept=".xlsx,.xls,.csv" required>
                        </label>
                      </div>
                    </div>

                    <!-- Template Download -->
                    <div class="flex justify-center">
                      <a href="#" class="text-sm text-indigo-600 hover:text-indigo-500">
                        Download template file
                      </a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="mt-5 sm:mt-6 flex space-x-3">
            <button type="button" 
                onclick="document.getElementById('si-upload-modal').classList.add('hidden')"
                class="inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                Cancel
            </button>
            <button type="button" onclick="handleFileUpload()"
                class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Upload
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

</x-app-layout>

<script>
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
        const total = allocationInfo.dataset.total;
        const available = parseInt(strong.dataset.available) - change;
        strong.dataset.available = available;
        strong.textContent = `${available} of ${total} available`;
        
        // Update color based on availability
        allocationInfo.className = `text-sm ${available > 0 ? 'text-blue-700' : 'text-red-700'}`;
    }
}

function addContainers() {
    let textarea = document.getElementById("container_list");
    let containerTypeSelect = document.getElementById("container_type");
    let containerType = containerTypeSelect.value;
    let containerTypeText = containerTypeSelect.options[containerTypeSelect.selectedIndex].text;
    let containerSection = document.getElementById("container-sections");

    if (!containerType) {
        alert("Please select a container type first.");
        return;
    }

    let lines = textarea.value.trim().split("\n");
    let containers = [];

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
        alert("Invalid format! Please enter containers in the format: CONTAINER,SEAL");
        return;
    }

    // Check available count
    const option = containerTypeSelect.selectedOptions[0];
    const availableCount = parseInt(option.dataset.available);
    if (containers.length > availableCount) {
        alert(`Only ${availableCount} containers available for this type`);
        return;
    }

    // Check if a section for the selected container type already exists
    let existingSection = document.querySelector(`div[data-container-type="${containerType}"]`);
    let containerGroup;
    
    if (!existingSection) {
        // Create new section
        existingSection = document.createElement("div");
        existingSection.setAttribute("data-container-type", containerType);
        existingSection.classList.add("border", "rounded-md", "mt-4", "bg-white", "shadow");
        
        // Create container group div first
        containerGroup = document.createElement("div");
        containerGroup.id = `container-group-${containerType}`;
        containerGroup.classList.add("space-y-3", "max-h-[400px]", "overflow-y-auto");
        
        // Set up the section HTML structure
        existingSection.innerHTML = `
            <div class="px-4 py-3 border-b">
                <h3 class="text-base font-semibold text-gray-900">${containerTypeText.split('(')[0].trim()}</h3>
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
    containers.forEach((entry, index) => {
        let div = document.createElement("div");
        div.classList.add("flex", "items-center", "gap-x-4", "w-full");

        div.innerHTML = `
            <input type="hidden" name="containers[${containerType}][${index}][container_type]" value="${containerType}">
            <input type="text" name="containers[${containerType}][${index}][container_number]" value="${entry.container}" 
                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 sm:text-sm/6">
            <input type="text" name="containers[${containerType}][${index}][seal_number]" value="${entry.seal}" 
                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 sm:text-sm/6">
            <button type="button" class="text-gray-400 hover:text-red-600" onclick="removeContainer(this)">
                <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                </svg>
            </button>
        `;

        containerGroup.appendChild(div);
    });

    // Update counts after adding containers
    updateContainerCounts(containerType, containers.length);

    // Clear textarea after processing
    textarea.value = "";
}

function removeContainer(button) {
    const containerItem = button.closest('.flex');
    if (containerItem) {
        // Get container type before removing the element
        const containerType = containerItem.querySelector('input[type="hidden"]').value;
        
        containerItem.remove();
        
        // Check if the container group is empty
        const containerSection = document.querySelector(`div[data-container-type="${containerType}"]`);
        const containerGroup = containerSection.querySelector(`#container-group-${containerType}`);
        
        // If no more items, remove the entire container section
        if (containerGroup && containerGroup.children.length === 0) {
            containerSection.remove();
        }

        // Update counts after removing container
        updateContainerCounts(containerType, -1);
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

// Add this new function
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Basic validation
    const requiredFields = [
        'box_operator',
        'shipper',
        'contact_shipper',
        'consignee',
        'contact_consignee',
        'notify_party',
        'notify_party_contact',
        'notify_party_address',
        'cargo_description',
        'hs_code'
    ];

    let isValid = true;
    requiredFields.forEach(field => {
        const element = document.getElementById(field);
        if (!element.value.trim()) {
            isValid = false;
            element.classList.add('outline-red-500');
        } else {
            element.classList.remove('outline-red-500');
        }
    });

    // Check if any containers have been added
    const containerSections = document.getElementById('container-sections');
    if (!containerSections.children.length) {
        isValid = false;
        alert('Please add at least one container');
        return;
    }

    if (isValid) {
        // Submit the form
        this.submit();
    } else {
        alert('Please fill in all required fields');
    }
});
</script>

