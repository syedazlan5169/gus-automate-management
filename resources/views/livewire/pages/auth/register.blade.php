<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $role = '';
    public string $company_name = '';
    public string $company_address = '';
    public string $company_phone = '';
    public string $industries = '';
    public ?string $other_industry = null;
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20'],
            'company_name' => ['required', 'string', 'max:255'],
            'company_address' => ['required', 'string', 'max:500'],
            'company_phone' => ['required', 'string', 'max:20'],
            'industries' => ['required', 'string', 'in:Fowarder,Trader,Supplier,other'],
            'other_industry' => ['required_if:industries,other', 'string', 'max:255', 'nullable'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        if ($user->role === 'customer') {
            $this->redirect(route('client.dashboard', absolute: false), navigate: true);
        } else {
            $this->redirect(route('admin.dashboard', absolute: false), navigate: true);
        }
    }
}; ?>

<div>
    <form x-data="{ industries: @entangle('industries') }" wire:submit="register">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input wire:model="phone" id="phone" class="block mt-1 w-full" type="tel" name="phone" required autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Company Name -->
        <div class="mt-4">
            <x-input-label for="company_name" :value="__('Company Name')" />
            <x-text-input wire:model="company_name" id="company_name" class="block mt-1 w-full" type="text" name="company_name" required />
            <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
        </div>

        <!-- Company Address -->
        <div class="mt-4">
            <x-input-label for="company_address" :value="__('Company Address')" />
            <x-text-input wire:model="company_address" id="company_address" class="block mt-1 w-full" type="text" name="company_address" required />
            <x-input-error :messages="$errors->get('company_address')" class="mt-2" />
        </div>

        <!-- Company Phone -->
        <div class="mt-4">
            <x-input-label for="company_phone" :value="__('Company Phone')" />
            <x-text-input wire:model="company_phone" id="company_phone" class="block mt-1 w-full" type="tel" name="company_phone" required />
            <x-input-error :messages="$errors->get('company_phone')" class="mt-2" />
        </div>

        <!-- Industries -->
        <div class="mt-4">
            <x-input-label for="industries" :value="__('Nature of Business')" />
            <select x-model="industries" wire:model.live="industries" id="industries" name="industries" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="">Select an industry</option>
                <option value="Fowarder">Fowarder</option>
                <option value="Trader">Trader</option>
                <option value="Supplier">Supplier</option>
                <option value="other">Other</option>
            </select>
            <x-input-error :messages="$errors->get('industries')" class="mt-2" />
        </div>

        <!-- Other Industry (shows only when "Other" is selected) -->
        <div class="mt-4" x-show="industries === 'other'">
            <x-input-label for="other_industry" :value="__('Specify Other Business Nature')" />
            <x-text-input id="other_industry" class="block mt-1 w-full" type="text" name="other_industry" />
            <x-input-error :messages="$errors->get('other_industry')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>
