<?php

namespace CalBird\TimeSheet;

interface TimeSheet
{
    public function save(TimeEntry $entry): void;
}
