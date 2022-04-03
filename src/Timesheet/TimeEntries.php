<?php

namespace CalBird\Timesheet;

use ArrayIterator;

final class TimeEntries implements \IteratorAggregate
{
    private array $entries;

    private function __construct(TimeEntry ...$entries)
    {
        $this->entries = $entries;
    }

    public static function fromArray(TimeEntry ...$entries): self
    {
        return new self($entries);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->entries);
    }
}
