<?php

namespace CalBird\Calendar;

use DateTimeInterface;

final class Event
{
    private EventId $eventId;
    private Description $description;
    private DateTimeInterface $start;
    private DateTimeInterface $end;

    public function __construct(EventId $eventId, Description $description, DateTimeInterface $start, DateTimeInterface $end)
    {
        $this->eventId = $eventId;
        $this->description = $description;
        $this->start = $start;
        $this->end = $end;
    }

    public function descriptionMatches(Description $description): bool
    {
        return $description->matches($this->description);
    }
}
