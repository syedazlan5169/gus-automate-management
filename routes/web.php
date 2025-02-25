<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'admin.dashboard')
    ->middleware(['auth', 'verified', 'staff.access'])
    ->name('admin.dashboard');

Route::view('client-portal', 'client.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('client.dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
