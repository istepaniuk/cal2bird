<?php

namespace CalBird\Timesheet;

use DateTimeInterface;

final class TimeEntry
{
    private EntryId $id;
    private DateTimeInterface $start;
    private DateTimeInterface $end;
    private Description $description;
    private Project $project;
    private bool $billable;

    public function __construct(
        EntryId $id,
        DateTimeInterface $start,
        DateTimeInterface $end,
        Description $description,
        Project $project,
        bool $billable = true
    ) {
        $this->id = $id;
        $this->start = $start;
        $this->end = $end;
        $this->description = $description;
        $this->project = $project;
        $this->billable = $billable;
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

    public function project(): Project
    {
        return $this->project;
    }

    public function billable(): bool
    {
        return $this->billable;
    }

    public function __toString(): string
    {
        return sprintf(
            '[%s] %s %s - %s',
            (string) $this->project,
            (string) $this->id,
            (string) $this->description,
            $this->start->format(DATE_ATOM)
        );
    }
}
