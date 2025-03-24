<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function submit(Request $request, Booking $booking)
    {
        try {
            $validated = $request->validate([
                'payment_file' => 'required|file|mimes:pdf,jpeg,png,jpg,heic,heif',
                'payment_date' => 'required|date',
                'payment_amount' => 'required|numeric',
                'payment_method' => 'required|string',
            ]);

            \DB::beginTransaction();

            $payment_file = $request->file('payment_file');
            
            // Generate filename using booking number and timestamp
            $fileName = $booking->invoice->invoice_number . '_' . date('Ymd_His') . '_payment_slip.' . $payment_file->getClientOriginalExtension();
            $payment_filePath = $payment_file->storeAs('payments', $fileName, 'public');

            // Log before creating invoice
            \Log::info('Attempting to create payment', [
                'booking_id' => $booking->id,
                'payment_file_path' => $payment_filePath,
                'file_name' => $fileName,
                'payment_data' => $validated
            ]);

            $payment = Payment::create([
                'invoice_id' => $booking->invoice->id,
                'payment_file' => $payment_filePath,
                'payment_date' => $validated['payment_date'],
                'payment_amount' => $validated['payment_amount'],
                'payment_method' => $validated['payment_method'],
                'status' => 'Pending Verification',
            ]);

            // Log after invoice creation
            \Log::info('Payment created successfully', [
                'payment_id' => $payment->id,
                'booking_id' => $booking->id
            ]);
            
            $booking->update(['status' => 'Payment Verification']);

            \DB::commit();

            return redirect()->route('booking.show', $booking)
                ->with('success', 'Payment submitted successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Invoice validation failed', [
                'booking_id' => $booking->id,
                'errors' => $e->errors()
            ]);
            return back()->withErrors($e->errors())
                        ->withInput();

        } catch (\Exception $e) {
            \DB::rollBack();
            
            // Log the detailed error
            \Log::error('Failed to submit invoice', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // If file was uploaded, attempt to remove it
            if (isset($invoicePath) && \Storage::disk('public')->exists($invoicePath)) {
                try {
                    \Storage::disk('public')->delete($invoicePath);
                    \Log::info('Cleaned up uploaded file', ['path' => $invoicePath]);
                } catch (\Exception $deleteError) {
                    \Log::error('Failed to delete uploaded file', [
                        'path' => $invoicePath,
                        'error' => $deleteError->getMessage()
                    ]);
                }
            }

            return back()->with('error', 'Error submitting invoice: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function download(Invoice $invoice)
    {
        // Check if invoice has a payment and payment file
        if (!$invoice->payment || !$invoice->payment->payment_file) {
            abort(404, 'Payment Slip not found');
        }

        // Use 'public' disk explicitly
        $filePath = $invoice->payment->payment_file;

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'Payment Slip not found');
        }

        $mimeType = Storage::disk('public')->mimeType($filePath);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        return Storage::disk('public')->download(
            $filePath,
            'Payment-' . $invoice->invoice_number . '.' . $extension,
            ['Content-Type' => $mimeType]
        );
    }
}
