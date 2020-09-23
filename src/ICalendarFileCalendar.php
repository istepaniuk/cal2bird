<?php

namespace CalBird;

use CalBird\Calendar\Calendar;
use CalBird\Calendar\Event;
use CalBird\Calendar\EventId;
use CalBird\Calendar\Events;
use CalBird\Calendar\Summary;
use DateTimeImmutable;
use Kigkonsult\Icalcreator\Vcalendar;
use Kigkonsult\Icalcreator\Vevent;

final class ICalendarFileCalendar implements Calendar
{
    private string $iCalFileName;

    public function __construct(string $iCalendarFileName)
    {
        $this->iCalFileName = $iCalendarFileName;
    }

    public function events(): Events
    {
        $vCalendar = Vcalendar::factory([Vcalendar::UNIQUE_ID => uniqid()]);
        $iCalContent = file_get_contents($this->iCalFileName);
        $vCalendar->parse($iCalContent);

        $from = new DateTimeImmutable('1111-01-01');
        $until = new DateTimeImmutable('9999-01-01');
        $allVEvents = $vCalendar->selectComponents($from, $until);

        return Events::fromArray(
            ...array_map(
                [$this, 'createEventFromVevent'],
                $this->flattenArray($allVEvents)
            )
        );
    }

    public function flattenArray(array $array)
    {
        $return = [];
        array_walk_recursive(
            $array,
            function ($a) use (&$return) {
                $return[] = $a;
            }
        );

        return $return;
    }

    private function createEventFromVevent(Vevent $vevent): Event
    {
        return new Event(
            EventId::fromString($vevent->getUid()),
            Summary::fromString($vevent->getSummary()),
            $vevent->getDtstart(),
            $vevent->getDtend()
        );
    }
}
