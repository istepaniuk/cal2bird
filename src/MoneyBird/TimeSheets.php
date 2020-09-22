<?php

namespace CalBird\MoneyBird;

interface TimeSheets
{
    public function save(TimeEntry $entry): void;
}
