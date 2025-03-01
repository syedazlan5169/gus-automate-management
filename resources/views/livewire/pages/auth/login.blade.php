<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest-split')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $user = auth()->user();
        
        if ($user->role === 'customer') {
            $this->redirect(route('client.dashboard'), navigate: true);
        } else {
            $this->redirect(route('admin.dashboard'), navigate: true);
        }
    }
}; ?>

<div class="flex min-h-screen">
    <!-- Left side - Image -->
    <div class="relative hidden w-1/2 lg:block">
        <img class="absolute inset-0 h-full w-full object-cover" src="{{ asset('images/sign-in-img-02.jpg') }}" alt="Background image">
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="absolute inset-0 bg-indigo-900/30 mix-blend-multiply"></div>
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-[#ec9b38]">GU Shipping Booking System</h1>
                <p class="mt-2 text-xl text-gray-300">Delivering Excellence Worldwide</p>
            </div>
        </div>
    </div>

    <!-- Right side - Login Form -->
    <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8 bg-gray-100">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <div class="mb-10 flex flex-col items-center">
                <a href="/" wire:navigate>
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
                <h2 class="mt-6 text-2xl/9 font-bold tracking-tight text-gray-900">Sign in to your account</h2>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form wire:submit="login" class="space-y-6">
                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <div class="flex justify-between items-center">
                        <x-input-label for="password" :value="__('Password')" />
                        @if (Route::has('password.request'))
                            <a class="text-sm text-blue-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}" wire:navigate>
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>

                    <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember" class="inline-flex items-center">
                        <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="mt-4">
                    <x-primary-button class="w-full justify-center">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>

            <p class="mt-10 text-center text-sm/6 text-gray-500">
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-indigo-500" wire:navigate>
                    {{ __('Create new account here.') }}
                </a>
            </p>
        </div>
    </div>
</div>
