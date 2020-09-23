<?php

namespace CalBird\Calendar;

use DateTimeInterface;

final class Event
{
    private EventId $eventId;
    private Summary $summary;
    private DateTimeInterface $start;
    private DateTimeInterface $end;

    public function __construct(EventId $eventId, Summary $summary, DateTimeInterface $start, DateTimeInterface $end)
    {
        $this->eventId = $eventId;
        $this->summary = $summary;
        $this->start = $start;
        $this->end = $end;
    }

    public function summaryMatches(Summary $summary): bool
    {
        return $summary->matches($this->summary);
    }

    public function summary(): Summary
    {
        return $this->summary;
    }

    public function id(): EventId
    {
        return $this->eventId;
    }
}
