<?php

namespace CalBird\Tests;

use CalBird\Calendar\Event;
use CalBird\Calendar\Description;
use CalBird\Calendar\EventId;
use DateTimeImmutable;
use CalBird\CalendarToTimesheetConnector;
use CalBird\MoneyBird\TimeSheet;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

final class CalendarToTimesheetConnectorTest extends TestCase
{
    use ProphecyTrait;

    public function test_it_creates_no_time_entries_if_there_are_no_matching_events()
    {
        $source = new FakeAndEmptyCalendar();
        $destination = $this->prophesize(TimeSheet::class);
        $connector = new CalendarToTimesheetConnector($source, $destination->reveal());

        $connector->createMatchingTimeEntries(Description::fromString('A project'));

        $destination->save(Argument::any())->shouldNotHaveBeenCalled();
    }

    public function test_it_creates_a_time_entries_if_an_event_description_matches_the_project_name()
    {
        $source = new FakeCalendar(
            new Event(
                EventId::fromString('test-id'),
                Description::fromString('A project'),
                $start = new DateTimeImmutable('2020-01-01 10:00:00'),
                $end = new DateTimeImmutable('2020-01-01 12:00:00')
            )
        );
        $destination = $this->prophesize(TimeSheet::class);
        $connector = new CalendarToTimesheetConnector($source, $destination->reveal());

        $connector->createMatchingTimeEntries(Description::fromString('A project'));

        $destination->save(Argument::any())->shouldHaveBeenCalledOnce();
    }

}
