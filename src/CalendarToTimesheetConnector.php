<?php

namespace CalBird;

use CalBird\MoneyBird\TimeSheets;

final class CalendarToTimesheetConnector
{
    private Calendar $source;
    private TimeSheets $destination;

    public function __construct(Calendar $source, TimeSheets $destination)
    {
        $this->source = $source;
        $this->destination = $destination;
    }

    public function createMatchingTimeEntries(string $projectName): void
    {
    }
}
