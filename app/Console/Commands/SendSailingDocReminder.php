<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Mail\SailingDocReminder;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendSailingDocReminder extends Command
{

    protected $signature = 'send:sailing:doc:reminder';
    protected $description = 'Send sailing doc reminder when the booking ets is less than 24 hours';

    /**
     * Execute the console command.
     */
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

        foreach ($bookings as $booking) {
            Mail::to(env('MAIL_TO_ADDRESS'))->send(new SailingDocReminder($booking));
            $this->info("Sent reminder for booking #{$booking->booking_number}");
        }

        $this->info('Reminder check completed.');
    }
}
