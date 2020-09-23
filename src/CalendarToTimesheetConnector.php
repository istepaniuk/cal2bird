<?php

namespace CalBird;


use CalBird\Calendar\Calendar;
use CalBird\Calendar\Description;
use CalBird\MoneyBird\TimeEntry;
use CalBird\MoneyBird\TimeSheet;

final class CalendarToTimesheetConnector
{
    private Calendar $source;
    private TimeSheet $destination;

    public function __construct(Calendar $source, TimeSheet $destination)
    {
        $this->source = $source;
        $this->destination = $destination;
    }

    public function createMatchingTimeEntries(Description $description): void
    {
        foreach ($this->source->events() as $event) {
            $this->destination->save(new TimeEntry());
        }
    }
}
