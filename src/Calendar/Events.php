<?php

namespace CalBird\Calendar;

use ArrayIterator;

final class Events implements \IteratorAggregate
{
    private array $events;

    private function __construct(Event ...$events)
    {
        $this->events = $events;
    }

    public static function fromArray(Event ...$events): self
    {
        return new self(...$events);
    }

    public static function empty(): self
    {
        return new self();
    }

    public function getIterator()
    {
        return new ArrayIterator($this->events);
    }
}
