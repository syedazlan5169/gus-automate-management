<x-app-layout>
    <div class="mx-auto flex w-full max-w-10xl flex-col px-4 py-10 sm:px-6 lg:px-8">
        <!-- Header section -->
        <div class="max-w-xl pb-8 space-y-2">
            <!-- Breadcrumb -->
            {{ Breadcrumbs::render('users.create') }}

            <!-- Heading -->
            <h1 id="create-user-heading" class="text-3xl font-bold tracking-tight text-gray-900">Create User</h1>
        </div>

        <!-- Content section with flex layout -->
        <div class="flex items-start gap-x-8">
            <!-- Left column area -->
            <main class="flex-1">
                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="px-4 py-5 sm:p-6">
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf
                            <div class="space-y-12">
                                <!-- User Information -->
                                <div class="border-b border-gray-900/10 pb-12 space-y-6">
                                    <h2 class="text-base font-semibold text-gray-900">User Information</h2>
                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <x-input-label for="name" value="Name" />
                                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
                                            @error('name')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="sm:col-span-3">
                                            <x-input-label for="email" value="Email" />
                                            <x-text-input id="email" name="email" type="text" class="mt-1 block w-full" required />
                                            @error('email')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="sm:col-span-3">
                                            <x-input-label for="phone" value="Phone" />
                                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" required />
                                            @error('phone')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="sm:col-span-3">
                                            <x-input-label for="role" value="Role" />
                                            <x-select-input id="role" name="role" class="mt-1 block w-full" required>
                                                @if(auth()->user()->role === 'admin')
                                                    <option value="admin">Admin</option>
                                                    <option value="finance">Finance</option>
                                                    <option value="manager">Manager</option>
                                                    <option value="customer">Customer</option>
                                                @elseif(auth()->user()->role === 'finance')
                                                    <option value="finance">Finance</option>
                                                    <option value="customer">Customer</option>
                                                @elseif(auth()->user()->role === 'manager')
                                                    <option value="manager">Manager</option>
                                                    <option value="customer">Customer</option>
                                                @endif
                                            </x-select-input>
                                            @error('role')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="sm:col-span-3">
                                            <x-input-label for="verified" value="Verified" />
                                            <x-select-input id="verified" name="verified" class="mt-1 block w-full" required>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
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
                                            <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full" required />
                                            @error('company_name')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="sm:col-span-3">
                                            <x-input-label for="company_address" value="Company Address" />
                                            <x-text-input id="company_address" name="company_address" type="text" class="mt-1 block w-full" required />
                                            @error('company_address')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="sm:col-span-3">
                                            <x-input-label for="company_phone" value="Company Phone" />
                                            <x-text-input id="company_phone" name="company_phone" type="text" class="mt-1 block w-full" required />
                                            @error('company_phone')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="sm:col-span-3">
                                            <x-input-label for="industries" value="Industries" />
                                            <x-select-input id="industries" name="industries" class="mt-1 block w-full" required>
                                                <option value="fowarder">Forwarder</option>
                                                <option value="trader">Trader</option>
                                                <option value="supplier">Supplier</option>
                                                <option value="other">Other</option>
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
                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <x-input-label for="password" value="Password" />
                                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                                            @error('password')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="sm:col-span-3">
                                            <x-input-label for="password_confirmation" value="Password Confirmation" />
                                            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
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
                                        Create User
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
