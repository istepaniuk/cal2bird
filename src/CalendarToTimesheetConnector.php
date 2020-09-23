<?php

namespace CalBird;

use CalBird\Calendar\Calendar;
use CalBird\Calendar\Event;
use CalBird\Calendar\Summary;
use CalBird\TimeSheet\TimeEntry;
use CalBird\TimeSheet\TimeSheet;

final class CalendarToTimesheetConnector
{
    private Calendar $source;
    private TimeSheet $destination;

    public function __construct(Calendar $source, TimeSheet $destination)
    {
        $this->source = $source;
        $this->destination = $destination;
    }

    public function createMatchingTimeEntries(Summary $summary): void
    {
        /** @var Event $event */
        foreach ($this->source->events() as $event) {
            if ($event->summaryMatches($summary)) {
                $this->destination->save(new TimeEntry());
            }
        }
    }
}
