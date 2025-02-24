<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'staff.access'])
    ->name('dashboard');

Route::view('client-portal', 'client-portal')
    ->middleware(['auth', 'verified'])
    ->name('client-portal');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
