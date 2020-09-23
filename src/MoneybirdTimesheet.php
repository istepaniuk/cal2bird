<?php

namespace CalBird;

use CalBird\TimeSheet\TimeEntry;
use CalBird\TimeSheet\TimeSheet;

final class MoneybirdTimesheet implements TimeSheet
{
    public function save(TimeEntry $entry): void
    {
        throw new \BadMethodCallException('Not implemented');
    }
}
