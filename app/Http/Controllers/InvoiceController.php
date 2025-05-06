<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceUploaded;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    // Invoice Submission
    public function store(Request $request, Booking $booking)
    {
        try {
            $validated = $request->validate([
                'invoice_file' => 'required|file|mimes:pdf',
                'invoice_date' => 'required|date',
                'invoice_number' => 'required|string',
                'invoice_amount' => 'required|numeric',
                'invoice_amount_usd' => 'nullable|numeric',
            ]);

            if ($request->input('invoice_name_select') == 'Other') {
                $validated['invoice_name'] = $request->input('invoice_name');
            }
            else {
                $validated['invoice_name'] = $request->input('invoice_name_select');
            }

            \DB::beginTransaction();

            $invoice = $request->file('invoice_file');
            
            // Generate filename using booking number and timestamp
            $fileName = $booking->booking_number . '_' . date('Ymd_His') . '_invoice.' . $invoice->getClientOriginalExtension();
            $invoicePath = $invoice->storeAs('invoices', $fileName, 'public');

            // Log before creating invoice
            \Log::info('Attempting to create invoice', [
                'booking_id' => $booking->id,
                'invoice_path' => $invoicePath,
                'file_name' => $fileName,
            ]);

            $invoice = Invoice::create([
                'booking_id' => $booking->id,
                'invoice_file' => $invoicePath,
                'invoice_name' => $validated['invoice_name'],
                'invoice_date' => $validated['invoice_date'],
                'invoice_number' => $validated['invoice_number'],
                'invoice_amount' => $validated['invoice_amount'],
                'payment_terms' => $request->input('payment_terms'),
                'invoice_amount_usd' => $validated['invoice_amount_usd'],
            ]);

            // Log after invoice creation
            \Log::info('Invoice created successfully', [
                'invoice_id' => $invoice->id,
                'booking_id' => $booking->id,
                'invoice_name' => $validated['invoice_name'],
                'invoice_date' => $validated['invoice_date'],
                'invoice_number' => $validated['invoice_number'],
                'invoice_amount' => $validated['invoice_amount'],
                'invoice_amount_usd' => $validated['invoice_amount_usd'],
            ]);
            
            \DB::commit();

            // Send email notification to customer
            Mail::to($booking->user->email)->send(new InvoiceUploaded($booking));

            return redirect()->route('booking.show', $booking)
                ->with('success', 'Invoice submitted successfully.');

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

    public function extract(Request $request, Booking $booking)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'invoice_file' => 'required|file|mimes:pdf|max:10240',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $parser = new Parser();
            $text = $parser->parseFile($request->file('invoice_file')->path())->getText();
            \Log::info('Normalized text: '.$text);

            $data = $this->extractInvoiceData($text);

            return response()->json([
                'success' => true,
                'invoice_date'   => $data['invoice_date'],
                'invoice_number' => $data['invoice_number'],
                'invoice_amount' => $data['invoice_amount'],
                'invoice_amount_usd' => $data['invoice_amount_usd'],
            ], 200);
        }
        catch (\Exception $e) {
            \Log::error($e);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function upload(Request $request, Booking $booking)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'invoice_file' => 'required|file|mimes:pdf|max:10240',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $file = $request->file('invoice_file');
            $filename = $file->getClientOriginalName();
            $file->move(public_path('invoices'), $filename);

            return response()->json([
                'success' => true,
                'message' => 'Invoice uploaded successfully',
                'filename' => $filename,
            ], 200);
        }
        catch (\Exception $e) {
            \Log::error($e);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function download(Invoice $invoice)
    {
        // Check if the invoice belongs to the current user's booking
        if ($invoice->booking->user_id !== auth()->id() && auth()->user()->role === 'customer') {
            abort(403);
        }

        // Check if invoice file exists
        if (!$invoice->invoice_file) {
            abort(404, 'Invoice file not found');
        }

        // Check if file exists in storage
        if (!Storage::disk('public')->exists($invoice->invoice_file)) {
            abort(404, 'Invoice file not found in storage');
        }

        // Get the mime type
        $mimeType = Storage::disk('public')->mimeType($invoice->invoice_file);

        // Generate a friendly filename
        $filename = 'Invoice-' . $invoice->invoice_name . '.pdf';

        // Return the file download response
        return Storage::disk('public')->download($invoice->invoice_file, $filename, [
            'Content-Type' => $mimeType
        ]);
    }

    public function destroy(Invoice $invoice)
    {
        // Check if the user has permission to delete this invoice
        if (auth()->user()->role === 'customer') {
            abort(403);
        }

        // Find the payment associated with the invoice (if any)
        $payment = Payment::where('invoice_id', $invoice->id)->first();

        // Delete the payment file from storage if it exists
        if ($payment && $payment->payment_file && Storage::disk('public')->exists($payment->payment_file)) {
            Storage::disk('public')->delete($payment->payment_file);
        }

        // Delete the payment record
        if ($payment) {
            $payment->delete();
        }

        // Delete the invoice file from storage
        if ($invoice->invoice_file && Storage::disk('public')->exists($invoice->invoice_file)) {
            Storage::disk('public')->delete($invoice->invoice_file);
        }

        // Delete the invoice record
        $invoice->delete();

        return redirect()->back()->with('success', 'Invoice deleted successfully');
    }

    public function downloadPayment(Payment $payment)
    {
        // Check if the payment belongs to the current user's booking
        if ($payment->invoice->booking->user_id !== auth()->id() && auth()->user()->role === 'customer') {
            abort(403);
        }

        // Check if payment file exists
        if (!$payment->payment_file) {
            abort(404, 'Payment file not found');
        }

        // Check if file exists in storage
        if (!Storage::disk('public')->exists($payment->payment_file)) {
            abort(404, 'Payment file not found in storage');
        }

        // Get the mime type
        $mimeType = Storage::disk('public')->mimeType($payment->payment_file);

        // Generate a friendly filename
        $filename = 'Payment-Receipt-' . $payment->invoice->invoice_number . '.' . pathinfo($payment->payment_file, PATHINFO_EXTENSION);

        // Return the file download response
        return Storage::disk('public')->download($payment->payment_file, $filename, [
            'Content-Type' => $mimeType
        ]);
    }

    private function extractInvoiceData(string $text): array
    {
        $normalized = str_replace("\xc2\xa0", ' ', $text);
        $normalized = preg_replace('/\.{2,}/', ' ', $normalized);
        $normalized = preg_replace('/\s+/', ' ', $normalized);

        $data = [
            'invoice_date' => null,
            'invoice_number' => null,
            'invoice_amount' => null,
            'invoice_amount_usd' => null,
        ];

        // Extract invoice number - new format "INVyyyy-mm-xxxx"
        if (preg_match('/INVOICE NO\.?\s*(INV\d{4}-\d{2}-\d{4})/i', $normalized, $m)) {
            $data['invoice_number'] = trim($m[1]);
        }

        // Extract date - new format "DD.MM.YYYY"
        if (preg_match('/DATE\s*(\d{2}\.\d{2}\.\d{4})/i', $normalized, $m)) {
            $date = str_replace('.', '-', $m[1]); // Convert to YYYY-MM-DD format
            $data['invoice_date'] = date('Y-m-d', strtotime($date));
        }

        // Extract USD amount first
        if (preg_match('/BALANCE DUE\s*USD\s*([\d,]+\.\d{2})/i', $normalized, $m)) {
            $data['invoice_amount_usd'] = (float) str_replace(',', '', $m[1]);
        }

        // Extract MYR amount
        if (preg_match('/MYR\s*([\d,]+\.\d{2})\s*(?:TAX SUMMARY|$)/i', $normalized, $m)) {
            $amount = (float) str_replace(',', '', $m[1]);
            if ($amount > 0) {
                $data['invoice_amount'] = $amount;
            }
        } 
        // If MYR not found but we have USD and exchange rate, calculate MYR
        elseif ($data['invoice_amount_usd'] && preg_match('/EXCHANGE RATE\s*:\s*([\d.]+)/i', $normalized, $rate)) {
            $exchangeRate = (float) $rate[1];
            $data['invoice_amount'] = round($data['invoice_amount_usd'] * $exchangeRate, 2);
        }

        return $this->validateExtractedData($data);
    }

    private function formatDate(string $date): string
    {
        $formats = ['M d, Y', 'd/m/Y', 'd-m-Y', 'Y/m/d', 'Y-m-d'];
        foreach ($formats as $fmt) {
            $d = \DateTime::createFromFormat($fmt, trim($date));
            if ($d) {
                return $d->format('Y-m-d');
            }
        }
        return trim($date);
    }

    private function validateExtractedData(array $data): array
    {
        // Only require invoice_amount (MYR) to be present
        if (!$data['invoice_amount']) {
            throw new \Exception("Could not find invoice amount in MYR");
        }
        
        // Other fields are optional
        if (!$data['invoice_date']) {
            throw new \Exception("Could not find invoice date");
        }
        if (!$data['invoice_number']) {
            throw new \Exception("Could not find invoice number");
        }
        
        return $data;
    }
}
