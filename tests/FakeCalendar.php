<?php

namespace CalBird\Tests;


use CalBird\Calendar\Calendar;
use CalBird\Calendar\Event;
use CalBird\Calendar\Events;

final class FakeCalendar implements Calendar
{
    private Events $events;

    public function __construct(Event $event)
    {
        $this->events = Events::fromArray($event);
    }

    public function events(): Events
    {
        return $this->events;
    }
}
