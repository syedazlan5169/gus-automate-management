<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Mail\BlTelexReleaseReminder;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendBlTelexReleaseReminders extends Command
{
    protected $signature = 'reminders:bl-telex-release';
    protected $description = 'Send reminders for BL with Telex Release documents that are due';

    public function handle()
    {
        $now = Carbon::now();
        $tomorrow = $now->copy()->addDay();

        $bookings = Booking::where('status', '>=', 4) // BL_CONFIRMED or higher
            ->where('ets', '>=', $now)
            ->where('ets', '<=', $tomorrow)
            ->whereDoesntHave('relatedDocuments', function ($query) {
                $query->where('document_name', 'BL with Telex Release');
            })
            ->get();

        $this->info('Found ' . $bookings->count() . ' bookings to send reminders for.');

        foreach ($bookings as $booking) {
            Mail::to(env('MAIL_TO_ADDRESS'))->send(new BlTelexReleaseReminder($booking));
            $this->info("Sent reminder for booking #{$booking->booking_number}");
        }

        $this->info('Reminder check completed.');
    }
} 