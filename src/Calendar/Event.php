<?php

namespace CalBird\Calendar;

use DateTimeInterface;

final class Event
{
    private EventId $eventId;
    private Summary $summary;
    private Description $description;
    private DateTimeInterface $start;
    private DateTimeInterface $end;

    public function __construct(
        EventId $eventId,
        Summary $summary,
        Description $description,
        DateTimeInterface $start,
        DateTimeInterface $end
    ) {
        $this->eventId = $eventId;
        $this->summary = $summary;
        $this->description = $description;
        $this->start = $start;
        $this->end = $end;
    }

    public function id(): EventId
    {
        return $this->eventId;
    }

    public function summary(): Summary
    {
        return $this->summary;
    }

    public function description(): Description
    {
        return $this->description;
    }

    public function start(): DateTimeInterface
    {
        return $this->start;
    }

    public function end(): DateTimeInterface
    {
        return $this->end;
    }

    public function summaryMatches(Summary $summary): bool
    {
        return $summary->matches($this->summary);
    }
}
