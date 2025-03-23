<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Storage;

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

        if (preg_match('/Invoice number[:\s.]*(\d+)/i', $normalized, $m)) {
            $data['invoice_number'] = trim($m[1]);
        }

        $datePatterns = [
            '/(\w+\s+\d{1,2},\s*\d{4})Invoice date/i',
            '/Invoice date[:\s.]*([A-Za-z]+\s+\d{1,2},\s*\d{4})/i',
            '/([A-Za-z]+\s+\d{1,2},\s*\d{4})\s*-\s*[A-Za-z]+\s+\d{1,2},\s*\d{4}/',
        ];

        foreach ($datePatterns as $pattern) {
            if (preg_match($pattern, $normalized, $m)) {
                $data['invoice_date'] = $this->formatDate($m[1]);
                if ($data['invoice_date']) {
                    break;
                }
            }
        }

        $amountPatterns = [
            // 1) Grab MYRxxx.xx that is directly followed by “Total in MYR”
            '/MYR([\d,]+\.\d{2})(?=\s*Total in MYR)/i',

            // 2) Or “Total in MYR MYRxxx.xx”
            '/Total in MYR\s*MYR([\d,]+\.\d{2})/i',

            // 3) Fallback — last resort only if context fails
            '/MYR([\d,]+\.\d{2})(?=\s|$)/i',
        ];

        foreach ($amountPatterns as $pattern) {
            if (preg_match($pattern, $normalized, $m)) {
                $amount = (float) str_replace(',', '', $m[1]);
                if ($amount > 0) {
                    $data['invoice_amount'] = $amount;
                    break;
                }
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
