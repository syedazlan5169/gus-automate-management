<?php

namespace App\Exports;

use App\Models\Booking;
use App\Models\BookingStatus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BookingsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $bookings;
    protected $maxInvoices;

    public function __construct()
    {
        // Load all bookings with their invoices
        $this->bookings = Booking::with('invoices')->get();

        // Get the highest number of invoices in a single booking
        $this->maxInvoices = $this->bookings->max(fn($booking) => $booking->invoices->count());
    }

    public function collection()
    {
        return $this->bookings;
    }

    public function headings(): array
    {
        $headings = [
            'Booking Date',
            'Booking Number',
            'Vessel',
            'Place of Receipt',
            'POL',
            'POD',
            'Voyage',
            'Place of Delivery',
            'ETS',
            'ETA',
            'Status',
        ];

        // Add dynamic invoice column headings
        for ($i = 1; $i <= $this->maxInvoices; $i++) {
            $headings[] = "Invoice Name $i";
            $headings[] = "Invoice Number $i";
            $headings[] = "Invoice Amount (MYR) $i";
            $headings[] = "Invoice Amount (USD) $i";
        }

        return $headings;
    }

    public function map($booking): array
    {
        $row = [
            $booking->booking_date?->format('Y-m-d'),
            $booking->booking_number,
            $booking->vessel,
            $booking->place_of_receipt,
            $booking->pol,
            $booking->pod,
            $booking->voyage,
            $booking->place_of_delivery,
            $booking->ets?->format('Y-m-d H:i'),
            $booking->eta?->format('Y-m-d H:i'),
            BookingStatus::labels($booking->status)[$booking->status] ?? '',
        ];

        // Add invoice data
        foreach ($booking->invoices as $invoice) {
            $row[] = $invoice->invoice_name ?? '';
            $row[] = $invoice->invoice_number ?? '';
            $row[] = $invoice->invoice_amount ?? '';
            $row[] = $invoice->invoice_amount_usd ?? '';
        }

        // Pad remaining invoice columns if fewer than max
        $remaining = $this->maxInvoices - $booking->invoices->count();
        for ($i = 0; $i < $remaining; $i++) {
            $row[] = ''; // invoice name
            $row[] = ''; // invoice number
            $row[] = ''; // amount MYR
            $row[] = ''; // amount USD
        }

        return $row;
    }
}



