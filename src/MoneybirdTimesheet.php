<?php

namespace CalBird;

use CalBird\Timesheet\TimeEntry;
use CalBird\Timesheet\Timesheet;

final class MoneybirdTimesheet implements Timesheet
{
    public function save(TimeEntry $entry): void
    {
        throw new \BadMethodCallException('Not implemented');
    }
}
