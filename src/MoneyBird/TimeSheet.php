<?php

namespace CalBird\MoneyBird;

interface TimeSheet
{
    public function save(TimeEntry $entry): void;
}
