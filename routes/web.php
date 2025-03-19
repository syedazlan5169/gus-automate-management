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
        Route::view('client-portal/booking/create', 'booking.create')->name('booking.create');
        Route::get('client-portal/booking/index', [BookingController::class, 'clientBookingIndex'])->name('client.bookings.index');

        // Booking routes
        Route::post('booking', [BookingController::class, 'store'])->name('booking.store');
        Route::post('booking/{booking}/edit', [BookingController::class, 'update'])->name('booking.update');
        Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('booking/{booking}', [BookingController::class, 'show'])->name('booking.show');

        // Shipping Instruction routes
        Route::get('bookings/{booking}/shipping-instructions/create', [ShippingInstructionController::class, 'create'])
            ->name('shipping-instructions.create');
        Route::post('bookings/{booking}/shipping-instructions', [ShippingInstructionController::class, 'store'])
            ->name('shipping-instructions.store');
        Route::get('shipping-instructions/{shippingInstruction}', [ShippingInstructionController::class, 'show'])
            ->name('shipping-instructions.show');
        Route::get('shipping-instructions/{shippingInstruction}/edit', [ShippingInstructionController::class, 'edit'])
            ->name('shipping-instructions.edit');
        Route::put('shipping-instructions/{shippingInstruction}', [ShippingInstructionController::class, 'update'])
            ->name('shipping-instructions.update');
        Route::get('shipping-instructions/{shippingInstruction}/bl', [ShippingInstructionController::class, 'generateBL'])
            ->name('shipping-instructions.bl');

        // New UI routes
        Route::view('client-portal/shipping-instructions/create-new-ui', 'shipping-instructions.create-new-ui-si')->name('shipping-instructions.create-new-ui');

        // Admin middleware Group - moved outside of verified middleware
        Route::middleware(['staff.access'])->group(function () {
            Route::view('dashboard', 'admin.dashboard')->name('admin.dashboard');
            // Add more admin routes here that need the same middleware
        });

    });
});

require __DIR__ . '/auth.php';
