<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;

class BookingStatus extends Model
{
    const CANCELLED = 0;
    const NEW = 1;
    const BOOKING_CONFIRMED = 2;
    const BL_VERIFICATION = 3;
    const BL_CONFIRMED = 4;
    const SAILING = 5;
    const ARRIVED = 6;
    const COMPLETED = 7;
   
    public static function labels($status)
    {
        return [
            self::NEW => 'New',
            self::BOOKING_CONFIRMED => 'Booking Confirmed',
            self::BL_VERIFICATION => 'BL Verification',
            self::BL_CONFIRMED => 'BL Confirmed',
            self::SAILING => 'Sailing',
            self::ARRIVED => 'Arrived',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        ];
    }

    public static function getAllStatuses()
    {
        return [
            self::NEW,
            self::BOOKING_CONFIRMED,
            self::BL_VERIFICATION,
            self::BL_CONFIRMED,
            self::SAILING,
            self::ARRIVED,
            self::COMPLETED,
            self::CANCELLED,
        ];
    }
}
