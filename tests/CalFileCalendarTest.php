<?php

namespace CalBird\Tests;

use CalBird\Calendar\Event;
use CalBird\ICalendarFileCalendar;
use PHPUnit\Framework\TestCase;

final class CalFileCalendarTest extends TestCase
{
    public function test_it_can_read_all_events_from_an_ics_file()
    {
        $calendar = new ICalendarFileCalendar(__DIR__.'/test.ics');

        $events = $calendar->events();

        $allDistinctIds = [];
        $allDistinctSummaries = [];
        /** @var Event $event */
        foreach ($events as $event) {
            $allDistinctIds[(string) $event->id()] = true;
            $allDistinctSummaries[(string) $event->summary()] = (string) $event->summary();
        }
        self::assertCount(51, $events);
        self::assertCount(51, $allDistinctIds);
        self::assertCount(2, $allDistinctSummaries);
        self::assertContains('ProjectX', $allDistinctSummaries);
        self::assertContains('ProjectY', $allDistinctSummaries);
    }
}
