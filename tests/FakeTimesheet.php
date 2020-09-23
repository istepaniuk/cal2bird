<?php

namespace CalBird\Tests;

use CalBird\Timesheet\TimeEntry;
use CalBird\Timesheet\Timesheet;

final class FakeTimesheet implements Timesheet
{
    /**
     * @var TimeEntry[]
     */
    public array $createdEntries = [];

    public function save(TimeEntry $entry): void
    {
        $this->createdEntries[] = $entry;
    }
}
