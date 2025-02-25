<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Auth middleware Group
Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')->name('profile');
    
    // Client middleware Group
    Route::middleware(['verified'])->group(function () {
        Route::view('client-portal', 'client.dashboard')->name('client.dashboard');
        
        // Admin middleware Group
        Route::middleware(['staff.access'])->group(function () {
            Route::view('dashboard', 'admin.dashboard')->name('admin.dashboard');
            // Add more admin routes here that need the same middleware
        });
    });
});

require __DIR__.'/auth.php';
