<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;

class BookingStatus extends Model
{
    const NEW = 1;
    const BOOKING_CONFIRMED = 2;
    const PENDING_SI = 3;
    const BL_GENERATED = 4;
    const BL_CONFIRMED = 5;
    const SAILING = 6;
    const ARRIVED = 7;
    const COMPLETED = 8;
    const CANCELLED = 9;
   
    public static function labels($status)
    {
        return [
            self::NEW => 'New',
            self::BOOKING_CONFIRMED => 'Booking Confirmed',
            self::PENDING_SI => 'Pending SI',
            self::BL_GENERATED => 'BL Generated',
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
            self::PENDING_SI,
            self::BL_GENERATED,
            self::BL_CONFIRMED,
            self::SAILING,
            self::ARRIVED,
            self::COMPLETED,
            self::CANCELLED,
        ];
    }
}
