<?php

namespace CalBird\Tests;

use CalBird\Calendar\Calendar;
use CalBird\Calendar\Event;
use CalBird\Calendar\Events;

final class FakeCalendar implements Calendar
{
    private Events $events;

    public function __construct(Event ...$event)
    {
        $this->events = Events::fromArray(...$event);
    }

    public function events(\DateTimeInterface $from): Events
    {
        return $this->events;
    }

    public function empty(): void
    {
        $this->events = Events::empty();
    }

    public function add(Event $event)
    {
        $this->events->add($event);
    }
}
