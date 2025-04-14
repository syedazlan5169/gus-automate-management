<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ShippingInstructionController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShippingRouteController;

Route::redirect('/', '/login');

// Auth middleware Group
Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')->name('profile');


    // Client middleware Group
    Route::middleware(['verified'])->group(function () {
        Route::view('client-portal', 'client.dashboard')->name('client.dashboard');

        // Booking routes
        Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::view('booking/create', 'booking.create')->name('booking.create');
        Route::post('booking', [BookingController::class, 'store'])->name('booking.store');
        Route::put('booking/{booking}/edit', [BookingController::class, 'update'])->name('booking.update');
        Route::get('booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
        Route::get('booking/{booking}/edit', [BookingController::class, 'edit'])->name('booking.edit');
        Route::get('booking/{booking}/confirm', [BookingController::class, 'confirmBooking'])->name('booking.confirm');
        Route::get('booking/{booking}/submit-si', [BookingController::class, 'submitSI'])->name('booking.submit-si');
        Route::post('booking/{booking}/submit-invoice', [BookingController::class, 'submitInvoice'])->name('booking.submit-invoice');
        Route::get('/booking/{booking}/payment/confirm', [BookingController::class, 'confirmPayment'])->name('booking.confirm-payment');
        Route::get('/booking/{booking}/payment/reject', [BookingController::class, 'rejectPayment'])->name('booking.reject-payment');

        // Invoice routes
        Route::post('/booking/{booking}/invoice/upload', [InvoiceController::class, 'upload'])->name('invoice.upload');
        Route::post('/booking/{booking}/invoice/extract', [InvoiceController::class, 'extract'])->name('invoice.extract');
        Route::get('/booking/{booking}/invoice/download', [InvoiceController::class, 'download'])->name('invoice.download');

        // Payment routes
        Route::post('/booking/{booking}/payment/submit', [PaymentController::class, 'submit'])->name('payment.submit');
        Route::get('/invoice/{invoice}/payment/download', [PaymentController::class, 'download'])->name('payment.download');

        // Shipping Instruction routes
        Route::get('bookings/{booking}/shipping-instructions/create', [ShippingInstructionController::class, 'create'])->name('shipping-instructions.create');
        Route::post('bookings/{booking}/shipping-instructions', [ShippingInstructionController::class, 'store'])->name('shipping-instructions.store');
        Route::get('shipping-instructions/{shippingInstruction}', [ShippingInstructionController::class, 'show'])->name('shipping-instructions.show');
        Route::delete('shipping-instructions/{shippingInstruction}', [ShippingInstructionController::class, 'destroy'])->name('shipping-instructions.destroy');
        Route::put('shipping-instructions/{shippingInstruction}', [ShippingInstructionController::class, 'update'])->name('shipping-instructions.update');
        Route::get('shipping-instructions/{shippingInstruction}/generate-bl', [ShippingInstructionController::class, 'generateBL'])->name('shipping-instructions.generate-bl');
        Route::get('shipping-instructions/{shippingInstruction}/generate-manifest', [ShippingInstructionController::class, 'generateManifest'])->name('shipping-instructions.generate-manifest');
        Route::get('shipping-instructions/{shippingInstruction}/edit', [ShippingInstructionController::class, 'edit'])
            ->name('shipping-instructions.edit');

        // Admin middleware Group - moved outside of verified middleware
        Route::middleware(['staff.access'])->group(function () {
            Route::view('dashboard', 'admin.dashboard')->name('admin.dashboard');

            // Shipping Routes
            Route::get('shipping-routes/create', [ShippingRouteController::class, 'create'])->name('shipping-routes.create');
            Route::post('shipping-routes', [ShippingRouteController::class, 'store'])->name('shipping-routes.store');
            Route::get('shipping-routes', [ShippingRouteController::class, 'index'])->name('shipping-routes.index');
            Route::get('shipping-routes/{shippingRoute}/edit', [ShippingRouteController::class, 'edit'])->name('shipping-routes.edit');
            Route::put('shipping-routes/{shippingRoute}', [ShippingRouteController::class, 'update'])->name('shipping-routes.update');
            Route::delete('shipping-routes/{shippingRoute}', [ShippingRouteController::class, 'destroy'])->name('shipping-routes.destroy');
        });

    });


    //Route::post('shipping-instructions/parse-container-list', [ShippingInstructionController::class, 'parseContainerList'])->name('shipping-instructions.parse-container-list');
    Route::post('shipping-instructions/parse-shipping-instruction', [ShippingInstructionController::class, 'parseShippingInstruction'])->name('shipping-instructions.parse-shipping-instruction');

    Route::get('shipping-instructions/download-template', [ShippingInstructionController::class, 'downloadTemplate'])->name('shipping-instructions.download-template');
});

require __DIR__ . '/auth.php';
