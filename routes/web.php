<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ShippingInstructionController;

Route::redirect('/', '/login');

// Auth middleware Group
Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')->name('profile');


    // Client middleware Group
    Route::middleware(['verified'])->group(function () {
        Route::view('client-portal', 'client.dashboard')->name('client.dashboard');
        Route::get('client-portal/bookings', [BookingController::class, 'clientBookingIndex'])->name('client.bookings.index');
        Route::get('bookings', [BookingController::class, 'adminBookingIndex'])->name('admin.bookings.index');

        // Booking routes
        Route::view('booking/create', 'booking.create')->name('booking.create');
        Route::post('booking', [BookingController::class, 'store'])->name('booking.store');
        Route::post('booking/{booking}/edit', [BookingController::class, 'update'])->name('booking.update');
        Route::get('booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
        Route::get('booking/{booking}/submit-si', [BookingController::class, 'submitSI'])->name('booking.submit-si');

        // Shipping Instruction routes
        Route::get('bookings/{booking}/shipping-instructions/create', [ShippingInstructionController::class, 'create'])->name('shipping-instructions.create');
        Route::post('bookings/{booking}/shipping-instructions', [ShippingInstructionController::class, 'store'])->name('shipping-instructions.store');
        Route::get('shipping-instructions/{shippingInstruction}', [ShippingInstructionController::class, 'show'])->name('shipping-instructions.show');
        Route::delete('shipping-instructions/{shippingInstruction}', [ShippingInstructionController::class, 'destroy'])->name('shipping-instructions.destroy');
        Route::get('shipping-instructions/{shippingInstruction}/edit', [ShippingInstructionController::class, 'edit'])
            ->name('shipping-instructions.edit');
        Route::put('shipping-instructions/{shippingInstruction}', [ShippingInstructionController::class, 'update'])
            ->name('shipping-instructions.update');

        // Admin middleware Group - moved outside of verified middleware
        Route::middleware(['staff.access'])->group(function () {
            Route::view('dashboard', 'admin.dashboard')->name('admin.dashboard');
            // Add more admin routes here that need the same middleware
        });

    });

    Route::post('shipping-instructions/parse-containers', [ShippingInstructionController::class, 'parseContainers'])
        ->name('shipping-instructions.parse-containers');

    Route::post('shipping-instructions/parse-container-list', [ShippingInstructionController::class, 'parseContainerList'])
        ->name('shipping-instructions.parse-container-list');

    Route::get('shipping-instructions/download-template', [ShippingInstructionController::class, 'downloadTemplate'])
        ->name('shipping-instructions.download-template');
});

require __DIR__ . '/auth.php';
