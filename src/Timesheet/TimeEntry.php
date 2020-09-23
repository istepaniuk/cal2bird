<?php

namespace CalBird\Timesheet;

use DateTimeInterface;

final class TimeEntry
{
    private EntryId $id;
    private DateTimeInterface $start;
    private DateTimeInterface $end;
    private Description $description;

    public function __construct(EntryId $id, DateTimeInterface $start, DateTimeInterface $end, Description $description)
    {
        $this->id = $id;
        $this->start = $start;
        $this->end = $end;
        $this->description = $description;
    }

    public function id(): EntryId
    {
        return $this->id;
    }

    public function start(): DateTimeInterface
    {
        return $this->start;
    }

    public function end(): DateTimeInterface
    {
        return $this->end;
    }

    public function description(): Description
    {
        return $this->description;
    }
}
