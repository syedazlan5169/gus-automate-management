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
                            <a href="#" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Users</a>
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
                                aria-current="page">Edit User</a>
                        </div>
                    </li>
                </ol>
            </nav>

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
                                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>Manager</option>
                                                <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>Staff</option>
                                                <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                            </x-select-input>
                                            @error('role')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="sm:col-span-3">
                                            <x-input-label for="verified" value="Verified" />
                                            <x-select-input id="verified" name="verified" class="mt-1 block w-full" required>
                                                <option value="1" {{ $user->verified ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ !$user->verified ? 'selected' : '' }}>No</option>
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
</x-app-layout>

