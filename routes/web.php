<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Auth middleware Group
Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')->name('profile');
    
    
    // Client middleware Group
    Route::middleware(['verified'])->group(function () {
        Route::view('client-portal', 'client.dashboard')->name('client.dashboard');
        Route::view('client-portal/booking/create', 'client.booking.create')->name('client.booking.create');
        Route::view('client-portal/booking/index', 'client.booking.index')->name('client.booking.index');

        // Admin middleware Group - moved outside of verified middleware
        Route::middleware(['staff.access'])->group(function () {
            Route::view('dashboard', 'admin.dashboard')->name('admin.dashboard');
            // Add more admin routes here that need the same middleware
        });

    });
});

require __DIR__.'/auth.php';
