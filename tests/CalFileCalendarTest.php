<?php

namespace CalBird\Tests;

use CalBird\Calendar\Event;
use CalBird\ICalendarFileCalendar;
use PHPUnit\Framework\TestCase;

final class CalFileCalendarTest extends TestCase
{
    private ICalendarFileCalendar $calendar;

    public function setUp(): void
    {
        $this->calendar = new ICalendarFileCalendar(__DIR__.'/test.ics');
    }

    public function test_it_can_read_all_events_from_an_ics_file()
    {
        $events = $this->calendar->events(new \DateTimeImmutable('2001-01-01'));

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

    public function test_it_can_read_events_from_an_ics_file_from_a_certain_date_on()
    {
        $events = $this->calendar->events(new \DateTimeImmutable('2020-09-01'));

        self::assertCount(36, $events);
    }

    public function test_it_returns_no_events_if_there_are_none()
    {
        $events = $this->calendar->events(new \DateTimeImmutable('2022-12-01'));

        self::assertCount(0, $events);
    }
}
