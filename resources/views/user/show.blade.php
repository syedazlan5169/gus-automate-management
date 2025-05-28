<x-app-layout>
    <div class="mx-auto flex w-full max-w-10xl flex-col px-4 py-10 sm:px-6 lg:px-8">
        <!-- Header section -->
        <div class="max-w-xl pb-8 space-y-2">
            <!-- Breadcrumb -->
            {{ Breadcrumbs::render('users.show', $user) }}

            <!-- Heading -->
            <h1 id="edit-user-heading" class="text-3xl font-bold tracking-tight text-gray-900">Edit User</h1>
        </div>

        <!-- Content section with flex layout -->
        <div class="flex items-start gap-x-8">
            <!-- Left column area -->
            <main class="flex-1">
                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="px-4 py-5 sm:p-6">
                        <form action="{{ route('users.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="space-y-12">
                                <!-- User Information -->
                                <div class="border-b border-gray-900/10 pb-12 space-y-6">
                                    <h2 class="text-base font-semibold text-gray-900">User Information</h2>
                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <x-input-label for="name" value="Name" />
                                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required value="{{ $user->name }}" />
                                            @error('name')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="sm:col-span-3">
                                            <x-input-label for="email" value="Email" />
                                            <x-text-input id="email" name="email" type="text" class="mt-1 block w-full" required value="{{ $user->email }}" />
                                            @error('email')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="sm:col-span-3">
                                            <x-input-label for="phone" value="Phone" />
                                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" required value="{{ $user->phone }}" />
                                            @error('phone')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="sm:col-span-3">
                                            <x-input-label for="role" value="Role" />
                                            <x-select-input id="role" name="role" class="mt-1 block w-full" required>
                                                @if(auth()->user()->role === 'admin')
                                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                    <option value="finance" {{ $user->role === 'finance' ? 'selected' : '' }}>Finance</option>
                                                    <option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>Manager</option>
                                                    <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                                @elseif(auth()->user()->role === 'finance')
                                                    <option value="finance" {{ $user->role === 'finance' ? 'selected' : '' }}>Finance</option>
                                                    <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                                @elseif(auth()->user()->role === 'manager')
                                                    <option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>Manager</option>
                                                    <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                                @endif
                                            </x-select-input>
                                            @error('role')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="sm:col-span-3">
                                            <x-input-label for="verified" value="Verified" />
                                            <x-select-input id="verified" name="verified" class="mt-1 block w-full" required>
                                                <option value="1" {{ $user->email_verified_at ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ !$user->email_verified_at ? 'selected' : '' }}>No</option>
                                            </x-select-input>
                                            @error('verified')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                    </div>
                                </div>

                                <!-- Company Information -->
                                <div class="border-b border-gray-900/10 pb-12 space-y-6">
                                    <h2 class="text-base font-semibold text-gray-900">Company Information</h2>
                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <x-input-label for="company_name" value="Company Name" />
                                            <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full" required value="{{ $user->company_name }}" />
                                            @error('company_name')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="sm:col-span-3">
                                            <x-input-label for="company_address" value="Company Address" />
                                            <x-text-input id="company_address" name="company_address" type="text" class="mt-1 block w-full" required value="{{ $user->company_address }}" />
                                            @error('company_address')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="sm:col-span-3">
                                            <x-input-label for="company_phone" value="Company Phone" />
                                            <x-text-input id="company_phone" name="company_phone" type="text" class="mt-1 block w-full" required value="{{ $user->company_phone }}" />
                                            @error('company_phone')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="sm:col-span-3">
                                            <x-input-label for="industries" value="Industries" />
                                            <x-select-input id="industries" name="industries" class="mt-1 block w-full" required>
                                                <option value="fowarder" {{ $user->industries === 'fowarder' ? 'selected' : '' }}>Forwarder</option>
                                                <option value="trader" {{ $user->industries === 'trader' ? 'selected' : '' }}>Trader</option>
                                                <option value="supplier" {{ $user->industries === 'supplier' ? 'selected' : '' }}>Supplier</option>
                                                <option value="other" {{ $user->industries === 'other' ? 'selected' : '' }}>Other</option>
                                            </x-select-input>
                                            @error('industries')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Credentials -->
                                <div class="border-b border-gray-900/10 pb-12 space-y-6">
                                    <h2 class="text-base font-semibold text-gray-900">Credentials</h2>
                                    <p class="text-sm text-gray-500">Leave password fields blank if you don't want to change the password.</p>
                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <x-input-label for="password" value="Password" />
                                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
                                            @error('password')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="sm:col-span-3">
                                            <x-input-label for="password_confirmation" value="Password Confirmation" />
                                            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" />
                                            @error('password_confirmation')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                                <!-- Submit button -->
                                <div class="mt-6 flex items-center justify-end gap-x-6">
                                    <button type="button" onclick="window.history.back()" class="text-sm font-semibold text-gray-900">Cancel</button>
                                    
                                    <button type="button" 
                                        onclick="openDeleteModal()"
                                        class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                                        Delete User
                                    </button>

                                    <button type="submit"
                                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                        Update User
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="relative z-10 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Delete User</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to delete this user? This action cannot be undone.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Delete</button>
                        </form>
                        <button type="button" onclick="closeDeleteModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
</x-app-layout>

