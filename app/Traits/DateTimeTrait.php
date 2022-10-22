<?php

namespace App\Traits;

use Carbon\Carbon;
use DateTime;

trait DateTimeTrait
{
  private $datetimeFormat = 'Y-m-d H:i:s';

  public function getStartEndTimeInUTC(string $timezone): array
  {
    // Get todays date in user timezone
    $todaysDate = Carbon::today($timezone);

    // Get start end time in UTC as according to timezone
    $utcStartDatetime = $todaysDate->copy()->timezone('UTC')->format($this->datetimeFormat);
    $utcEndDatetime = $todaysDate->copy()->endOfDay()->timezone('UTC')->format($this->datetimeFormat);

    return [$utcStartDatetime, $utcEndDatetime];
  }

  public function convertToDatetime(string $datetime)
  {
    // return new DateTime($datetime);
    return date($this->datetimeFormat, strtotime($datetime));
  }
}
