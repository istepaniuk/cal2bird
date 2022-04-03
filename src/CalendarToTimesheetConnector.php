<?php

namespace CalBird;

use CalBird\Calendar\Calendar;
use CalBird\Calendar\Event;
use CalBird\Calendar\Summary;
use CalBird\Timesheet\Description;
use CalBird\Timesheet\EntryId;
use CalBird\Timesheet\Project;
use CalBird\Timesheet\TimeEntry;
use CalBird\Timesheet\Timesheet;
use DateTimeInterface;

final class CalendarToTimesheetConnector
{
    private Calendar $source;
    private Timesheet $destination;

    public function __construct(Calendar $source, Timesheet $destination)
    {
        $this->source = $source;
        $this->destination = $destination;
    }

    public function createMatchingTimeEntries(Summary $summary, DateTimeInterface $from): void
    {
        /** @var Event $event */
        foreach ($this->source->events($from) as $event) {
            if ($event->summaryMatches($summary)) {
                $this->destination->save($this->timeEntryFromCalendarEvent($event));
            }
        }
    }

    private function timeEntryFromCalendarEvent(Event $event): TimeEntry
    {

        return new TimeEntry(
            EntryId::fromString((string) $event->id()),
            $event->start(),
            $event->end(),
            $this->timeEntryDescriptionFromEventDescription($event),
            Project::fromString((string) $event->summary())
        );
    }

    public function createAllTimeEntriesIntoNonBillable(DateTimeInterface $from): void
    {
        /** @var Event $event */
        foreach ($this->source->events($from) as $event) {
            $this->destination->save($this->nonBillableTimeEntryFromCalendarEvent($event));
        }
    }

    private function nonBillableTimeEntryFromCalendarEvent(Event $event): TimeEntry
    {
        $description = (string) $event->summary();
        if (!$event->description()->isEmpty()) {
            $description .= ': '.$event->description();
        }

        return new TimeEntry(
            EntryId::fromString((string) $event->id()),
            $event->start(),
            $event->end(),
            Description::fromString($description),
            Project::none(),
            false
        );
    }

    private function timeEntryDescriptionFromEventDescription(Event $event): Description
    {
        if ($event->description()->isEmpty()) {
            return Description::fromString((string) $event->summary());
        }

        return Description::fromString((string) $event->description());
    }
}
