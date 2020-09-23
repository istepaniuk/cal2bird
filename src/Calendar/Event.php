<?php

namespace CalBird\Calendar;

use DateTimeInterface;

final class Event
{
    public function __construct(EventId $eventId, Description $description, DateTimeInterface $start, DateTimeInterface $end)
    {
    }
}
