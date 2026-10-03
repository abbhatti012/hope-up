<?php

namespace App\Traits;

use Carbon\Carbon;
use DateTimeInterface;

trait FormatsDates
{
    /**
     * Prepare a date for array / JSON serialization.
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return Carbon::parse($date)->format('d-M-Y h:i A'); // customise as needed
    }
}
