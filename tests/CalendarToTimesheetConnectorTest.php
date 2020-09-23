<?php

namespace CalBird\Tests;

use CalBird\Calendar\Event;
use CalBird\Calendar\EventId;
use CalBird\Calendar\Summary;
use CalBird\CalendarToTimesheetConnector;
use CalBird\Timesheet\Timesheet;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

final class CalendarToTimesheetConnectorTest extends TestCase
{
    use ProphecyTrait;

    private CalendarToTimesheetConnector $connector;
    private FakeCalendar $source;
    private $destination;

    public function setUp(): void
    {
        $this->source = new FakeCalendar();
        $this->destination = $this->prophesize(Timesheet::class);

        $this->connector = $connector = new CalendarToTimesheetConnector(
            $this->source,
            $this->destination->reveal()
        );
    }

    public function test_it_creates_no_time_entries_if_there_are_no_matching_events()
    {
        $this->source->empty();

        $this->connector->createMatchingTimeEntries(Summary::fromString('A project'));

        $this->destination->save(Argument::any())->shouldNotHaveBeenCalled();
    }

    public function test_it_creates_a_time_entries_if_an_event_description_matches_the_project_name()
    {
        $this->source->add(
            new Event(
                EventId::fromString('test-id'),
                Summary::fromString('A project'),
                $start = new DateTimeImmutable('2020-01-01 10:00:00'),
                $end = new DateTimeImmutable('2020-01-01 12:00:00')
            )
        );

        $this->connector->createMatchingTimeEntries(Summary::fromString('A project'));

        $this->destination->save(Argument::any())->shouldHaveBeenCalledOnce();
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

        $this->connector->createMatchingTimeEntries(Summary::fromString('Unknown project'));

        $this->destination->save(Argument::any())->shouldNotHaveBeenCalled();
    }
}
