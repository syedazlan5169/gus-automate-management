<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\UploadPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ActivityLog;

class PaymentController extends Controller
{
    public function submit(Request $request, Invoice $invoice)
    {
        try {
            $validated = $request->validate([
                'payment_file' => 'required|file|mimes:pdf,jpeg,png,jpg,heic,heif',
                'payment_date' => 'required|date',
                'payment_amount' => 'required|numeric',
                'payment_method' => 'required|string',
            ]);

            DB::beginTransaction();

            $payment_file = $request->file('payment_file');
            
            // Generate filename using invoice number and timestamp
            $fileName = $invoice->invoice_number . '_' . date('Ymd_His') . '_payment_slip.' . $payment_file->getClientOriginalExtension();
            $payment_filePath = $payment_file->storeAs('payments', $fileName, 'public');

            // Log before creating invoice
            Log::info('Attempting to create payment', [
                'invoice_id' => $invoice->id,
                'payment_file_path' => $payment_filePath,
                'file_name' => $fileName,
                'payment_data' => $validated
            ]);

            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'payment_file' => $payment_filePath,
                'payment_date' => $validated['payment_date'],
                'payment_amount' => $validated['payment_amount'],
                'payment_method' => $validated['payment_method'],
            ]);

            // Log after invoice creation
            Log::info('Payment created successfully', [
                'payment_id' => $payment->id,
                'invoice_id' => $invoice->id
            ]);
            

            DB::commit();
            ActivityLog::logPaymentUploaded(auth()->user(), $invoice->booking, $payment);

            Mail::to(config('mail.admin_to'))->send(new UploadPayment($invoice->booking, $payment, $invoice));
            $invoice->update(['status' => 'Paid']);

            return redirect()->route('booking.show', $invoice->booking)
                ->with('success', 'Payment submitted successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Invoice validation failed', [
                'invoice_id' => $invoice->id,
                'errors' => $e->errors()
            ]);
            return back()->withErrors($e->errors())
                        ->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the detailed error
            Log::error('Failed to submit invoice', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // if file was uploaded, attempt to remove it
            if (isset($payment_filePath) && Storage::disk('public')->exists($payment_filePath)) {
                Storage::disk('public')->delete($payment_filePath);
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

        return response()->download(
            Storage::disk('public')->path($filePath),
            'Payment-' . $invoice->invoice_number . '.' . $extension,
            ['Content-Type' => $mimeType]
        );
    }
}
