<?php

namespace CalBird\Timesheet;

interface Timesheet
{
    public function save(TimeEntry $entry): void;
}
