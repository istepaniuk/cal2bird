<?php

namespace CalBird\Tests;

use CalBird\Calendar\Event;
use CalBird\Calendar\EventId;
use CalBird\Calendar\Summary;
use CalBird\CalendarToTimesheetConnector;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class CalendarToTimesheetConnectorTest extends TestCase
{
    private CalendarToTimesheetConnector $connector;
    private FakeCalendar $source;
    private FakeTimesheet $destination;

    public function setUp(): void
    {
        $this->source = new FakeCalendar();
        $this->destination = new FakeTimesheet();

        $this->connector = $connector = new CalendarToTimesheetConnector(
            $this->source,
            $this->destination
        );
    }

    public function test_it_creates_no_time_entries_if_there_are_no_matching_events()
    {
        $this->source->empty();

        $this->connector->createMatchingTimeEntries(Summary::fromString('A project'), new DateTimeImmutable('2001'));

        self::assertEmpty($this->destination->createdEntries);
    }

    public function test_it_creates_a_time_entries_if_an_event_description_matches_the_project_name()
    {
        $this->source->add(
            new Event(
                EventId::fromString($id = 'test-id'),
                Summary::fromString($summary = 'A project'),
                $start = new DateTimeImmutable('2020-01-01 10:00:00'),
                $end = new DateTimeImmutable('2020-01-01 12:00:00')
            )
        );

        $this->connector->createMatchingTimeEntries(Summary::fromString('A project'), new DateTimeImmutable('2001'));

        self::assertCount(1, $this->destination->createdEntries);
        $addedEntry = reset($this->destination->createdEntries);
        self::assertEquals($id, $addedEntry->id());
        self::assertEquals($summary, $addedEntry->description());
        self::assertEquals($start, $addedEntry->start());
        self::assertEquals($end, $addedEntry->end());
    }

    public function test_it_does_not_creates_a_time_entry_if_the_event_description_does_not_match()
    {
        $this->source->add(
            new Event(
                EventId::fromString('test-id'),
                Summary::fromString('A project'),
                $start = new DateTimeImmutable('2020-01-01 10:00:00'),
                $end = new DateTimeImmutable('2020-01-01 12:00:00')
            )
        );

        $this->connector->createMatchingTimeEntries(Summary::fromString('Unknown project'), new DateTimeImmutable('2001'));

        self::assertEmpty($this->destination->createdEntries);
    }
}
