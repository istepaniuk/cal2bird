<?php

namespace CalBird\Tests;

use CalBird\Calendar\Description;
use CalBird\Calendar\Event;
use CalBird\Calendar\EventId;
use CalBird\CalendarToTimesheetConnector;
use CalBird\TimeSheet\TimeSheet;
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
        $this->destination = $this->prophesize(TimeSheet::class);

        $this->connector = $connector = new CalendarToTimesheetConnector(
            $this->source,
            $this->destination->reveal()
        );
    }

    public function test_it_creates_no_time_entries_if_there_are_no_matching_events()
    {
        $this->source->empty();

        $this->connector->createMatchingTimeEntries(Description::fromString('A project'));

        $this->destination->save(Argument::any())->shouldNotHaveBeenCalled();
    }

    public function test_it_creates_a_time_entries_if_an_event_description_matches_the_project_name()
    {
        $this->source->add(
            new Event(
                EventId::fromString('test-id'),
                Description::fromString('A project'),
                $start = new DateTimeImmutable('2020-01-01 10:00:00'),
                $end = new DateTimeImmutable('2020-01-01 12:00:00')
            )
        );

        $this->connector->createMatchingTimeEntries(Description::fromString('A project'));

        $this->destination->save(Argument::any())->shouldHaveBeenCalledOnce();
    }
}
