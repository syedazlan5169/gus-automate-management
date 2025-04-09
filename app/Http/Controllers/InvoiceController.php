<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class InvoiceController extends Controller
{
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

    public function download(Booking $booking)
    {
        // Check if booking has an invoice
        if (!$booking->invoice || !$booking->invoice->invoice_file) {
            abort(404, 'Invoice not found');
        }

        // Use 'public' disk explicitly
        $filePath = $booking->invoice->invoice_file;

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'Invoice file not found');
        }

        $mimeType = Storage::disk('public')->mimeType($filePath);

        return Storage::disk('public')->download(
            $filePath,
            'Invoice-' . $booking->booking_number . '.pdf',
            ['Content-Type' => $mimeType]
        );
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

        // Extract amount - looking for both USD and MYR amounts
        // First try to get MYR amount as it's the final converted amount
        if (preg_match('/MYR\s*([\d,]+\.\d{2})\s*(?:TAX SUMMARY|$)/i', $normalized, $m)) {
            $amount = (float) str_replace(',', '', $m[1]);
            if ($amount > 0) {
                $data['invoice_amount'] = $amount;
            }
        } 
        // If MYR not found, try USD amount
        elseif (preg_match('/BALANCE DUE\s*USD\s*([\d,]+\.\d{2})/i', $normalized, $m)) {
            // If we find exchange rate, convert to MYR
            if (preg_match('/EXCHANGE RATE\s*:\s*([\d.]+)/i', $normalized, $rate)) {
                $usdAmount = (float) str_replace(',', '', $m[1]);
                $exchangeRate = (float) $rate[1];
                $data['invoice_amount'] = round($usdAmount * $exchangeRate, 2);
            } else {
                // If no exchange rate found, just use USD amount
                $data['invoice_amount'] = (float) str_replace(',', '', $m[1]);
            }
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
        foreach ($data as $key => $value) {
            if (!$value) {
                throw new \Exception("Could not find invoice {$key}");
            }
        }
        return $data;
    }
}
