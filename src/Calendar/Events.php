<?php

namespace CalBird\Calendar;

use ArrayIterator;

final class Events implements \IteratorAggregate
{
    /**
     * @var Event[]
     */
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

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->events);
    }

    public function add(Event $event): void
    {
        $this->events[] = $event;
    }
}
