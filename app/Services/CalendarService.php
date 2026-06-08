<?php

namespace App\Services;


class CalendarService
{

    public function getEventsByActivityId(int $activityId)
    {
        return config('events');
    }
  
}
