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
                      <div class="mt-2 space-y-1">
                        @foreach($booking->cargos as $cargo)
                          @php
                            $allocatedCount = $cargo->containers->whereNotNull('shipping_instruction_id')->count();
                            $availableCount = $cargo->container_count - $allocatedCount;
                          @endphp
                          <p class="text-sm {{ $availableCount > 0 ? 'text-blue-700' : 'text-red-700' }}">
                            {{ $cargo->container_type }}: 
                            <strong>{{ $availableCount }} of {{ $cargo->container_count }} available</strong>
                          </p>
                        @endforeach
                      </div>
                    </div>
                    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                      <button type="button"
                        onclick="document.getElementById('si-upload-modal').classList.remove('hidden')"
                        class="inline-flex items-center gap-x-1.5 rounded-md bg-blue-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 uppercase tracking-widest">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                          stroke="currentColor" class="w-4 h-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Upload Details
                      </button>
                    </div>
                  </div>

                  <!-- Container sections will be populated here -->
                  <div id="container-sections">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Action Buttons -->
          <div class="overflow-hidden rounded-lg bg-white shadow">
            <div class="px-4 py-5 sm:p-6">
              <div class="space-y-6">
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
                    <span class="ml-4 flex min-w-0 flex-col">

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

@push('scripts')
<script>
document.getElementById('container_type').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const availableCount = selectedOption.dataset.available;
    const quantityInput = document.getElementById('quantity');
    
    if (availableCount) {
        quantityInput.max = availableCount;
        quantityInput.value = Math.min(quantityInput.value || 1, availableCount);
    }
});

function closeModal() {
    document.getElementById('si-upload-modal').classList.add('hidden');
    document.getElementById('upload-form').reset();
}

function handleFileUpload() {
    const form = document.getElementById('upload-form');
    const formData = new FormData(form);

    // Show loading state if needed

    fetch('{{ route("shipping-instructions.parse-containers") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add container section to the page
            addContainerSection(data.containers);
            closeModal();
        } else {
            alert(data.message || 'Error processing file');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error uploading file');
    });
}

function addContainerSection(containers) {
    const containerType = document.getElementById('container_type').options[document.getElementById('container_type').selectedIndex].text;
    const section = document.createElement('div');
    section.className = 'mt-4';
    
    const html = `
        <div class="sm:flex sm:items-center mt-4">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold text-gray-900">${containerType}</h1>
            </div>
        </div>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-300">
                <thead>
                    <tr>
                        <th class="w-12 px-3 py-3.5">
                            <input type="checkbox" class="container-type-checkbox" onchange="toggleAllContainers(this)">
                        </th>
                        <th class="w-1/2 py-3.5 text-left text-sm font-semibold text-gray-900">Container Number</th>
                        <th class="w-1/2 px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Seal Number</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    ${containers.map(container => `
                        <tr>
                            <td class="px-3 py-4">
                                <input type="checkbox" name="selected_containers[]" value="${container.number}" class="container-checkbox">
                            </td>
                            <td class="px-3 py-4">
                                <input type="text" name="container_numbers[]" value="${container.number}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" readonly>
                            </td>
                            <td class="px-3 py-4">
                                <input type="text" name="seal_numbers[]" value="${container.seal || ''}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;
    
    section.innerHTML = html;
    document.getElementById('container-sections').appendChild(section);
}

function toggleAllContainers(checkbox) {
    const section = checkbox.closest('table').querySelector('tbody');
    const checkboxes = section.querySelectorAll('.container-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
}
</script>
@endpush