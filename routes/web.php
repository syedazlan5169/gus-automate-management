<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ShippingInstructionController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShippingRouteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\RelatedDocumentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceController;

Route::redirect('/', '/login');

// Auth middleware Group
Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')->name('profile');


    // Client middleware Group
    Route::middleware(['verified'])->group(function () {
        Route::get('client-portal', [DashboardController::class, 'clientDashboard'])->name('client.dashboard');

        // Booking routes
        Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::view('booking/create', 'booking.create')->name('booking.create');
        Route::post('booking', [BookingController::class, 'store'])->name('booking.store');
        Route::put('booking/{booking}/edit', [BookingController::class, 'update'])->name('booking.update');
        Route::get('booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
        Route::get('booking/{booking}/edit', [BookingController::class, 'edit'])->name('booking.edit');
        Route::get('booking/{booking}/confirm', [BookingController::class, 'confirmBooking'])->name('booking.confirm');
        Route::get('booking/{booking}/submit', [BookingController::class, 'submitBooking'])->name('booking.submit');
        Route::get('booking/{booking}/confirm-bl', [BookingController::class, 'confirmBL'])->name('booking.confirm-bl');
        Route::get('booking/{booking}/submit-si', [BookingController::class, 'submitSI'])->name('booking.submit-si');
        Route::get('/booking/{booking}/payment/confirm', [BookingController::class, 'confirmPayment'])->name('booking.confirm-payment');
        Route::get('/booking/{booking}/payment/reject', [BookingController::class, 'rejectPayment'])->name('booking.reject-payment');
        Route::get('/booking/{booking}/sailing', [BookingController::class, 'sailing'])->name('booking.sailing');
        Route::get('/booking/{booking}/arrived', [BookingController::class, 'arrived'])->name('booking.arrived');
        Route::get('/booking/{booking}/completed', [BookingController::class, 'completed'])->name('booking.completed');
        Route::get('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
        Route::delete('/booking/{booking}/delete', [BookingController::class, 'destroy'])->name('booking.delete');

        // Related Document routes
        Route::post('/booking/{booking}/related-document/upload', [RelatedDocumentController::class, 'store'])->name('related-document.upload');
        Route::delete('/booking/{booking}/related-document/{document}/delete', [RelatedDocumentController::class, 'destroy'])->name('related-document.delete');
        Route::get('/booking/{booking}/related-document/{document}/download', [RelatedDocumentController::class, 'download'])->name('related-document.download');


        // Invoice routes
        Route::post('booking/{booking}/invoice/submit', [InvoiceController::class, 'store'])->name('invoice.submit');
        Route::delete('invoice/{invoice}/delete', [InvoiceController::class, 'destroy'])->name('invoice.delete');
        Route::post('/booking/{booking}/invoice/upload', [InvoiceController::class, 'upload'])->name('invoice.upload');
        Route::post('/booking/{booking}/invoice/extract', [InvoiceController::class, 'extract'])->name('invoice.extract');
        Route::get('invoice/{invoice}/download', [InvoiceController::class, 'download'])->name('invoice.download');
        Route::get('/invoice/payment/{payment}/download', [InvoiceController::class, 'downloadPayment'])->name('invoice.payment.download');

        // Payment routes
        Route::post('/invoice/payment/submit/{invoice}', [PaymentController::class, 'submit'])->name('payment.submit');
        Route::get('/invoice/{invoice}/payment/download', [PaymentController::class, 'download'])->name('payment.download');

        // Shipping Instruction routes
        Route::get('bookings/{booking}/shipping-instructions/create', [ShippingInstructionController::class, 'create'])->name('shipping-instructions.create');
        Route::post('bookings/{booking}/shipping-instructions', [ShippingInstructionController::class, 'store'])->name('shipping-instructions.store');
        Route::get('shipping-instructions/{shippingInstruction}', [ShippingInstructionController::class, 'show'])->name('shipping-instructions.show');
        Route::delete('shipping-instructions/{shippingInstruction}', [ShippingInstructionController::class, 'destroy'])->name('shipping-instructions.destroy');
        Route::put('shipping-instructions/{shippingInstruction}', [ShippingInstructionController::class, 'update'])->name('shipping-instructions.update');
        Route::get('shipping-instructions/{shippingInstruction}/generate-bl', [ShippingInstructionController::class, 'generateBL'])->name('shipping-instructions.generate-bl');
        Route::get('shipping-instructions/{shippingInstruction}/generate-telex-bl', [ShippingInstructionController::class, 'generateTelexBL'])->name('shipping-instructions.generate-telex-bl');
        Route::get('shipping-instructions/{shippingInstruction}/generate-manifest', [ShippingInstructionController::class, 'generateManifest'])->name('shipping-instructions.generate-manifest');
        Route::get('shipping-instructions/{shippingInstruction}/release-telex-bl', [ShippingInstructionController::class, 'releaseTelexBL'])->name('shipping-instructions.release-telex-bl');
        Route::get('shipping-instructions/{shippingInstruction}/edit', [ShippingInstructionController::class, 'edit'])
            ->name('shipping-instructions.edit');

        // Admin middleware Group - moved outside of verified middleware
        Route::middleware(['staff.access'])->group(function () {
            Route::get('dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
            Route::get('export-bookings', [DashboardController::class, 'exportBookings'])->name('export-bookings');

            // Shipping Routes
            Route::get('shipping-routes/create', [ShippingRouteController::class, 'create'])->name('shipping-routes.create');
            Route::post('shipping-routes', [ShippingRouteController::class, 'store'])->name('shipping-routes.store');
            Route::get('shipping-routes', [ShippingRouteController::class, 'index'])->name('shipping-routes.index');
            Route::get('shipping-routes/{shippingRoute}/edit', [ShippingRouteController::class, 'edit'])->name('shipping-routes.edit');
            Route::put('shipping-routes/{shippingRoute}', [ShippingRouteController::class, 'update'])->name('shipping-routes.update');
            Route::delete('shipping-routes/{shippingRoute}', [ShippingRouteController::class, 'destroy'])->name('shipping-routes.destroy');

            // User routes
            Route::get('users', [UserController::class, 'index'])->name('users.index');
            Route::get('users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('users', [UserController::class, 'store'])->name('users.store');
            Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show')->middleware('check.user.view');
            Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

            // Finance routes
            Route::get('finance', [FinanceController::class, 'index'])->name('finance.index');
        });

    });


    //Route::post('shipping-instructions/parse-container-list', [ShippingInstructionController::class, 'parseContainerList'])->name('shipping-instructions.parse-container-list');
    Route::post('shipping-instructions/parse-shipping-instruction', [ShippingInstructionController::class, 'parseShippingInstruction'])->name('shipping-instructions.parse-shipping-instruction');

    Route::get('shipping-instructions/download-template', [ShippingInstructionController::class, 'downloadTemplate'])->name('shipping-instructions.download-template');

    
});

// Test email route
Route::get('/test-email', function () {
    try {
        Mail::raw('This is a test email from your application.', function($message) {
            $message->to('logicgame1001@gmail.com')
                   ->subject('Test Email');
        });
        return 'Test email sent successfully!';
    } catch (\Exception $e) {
        return 'Error sending email: ' . $e->getMessage();
    }
});

require __DIR__ . '/auth.php';
