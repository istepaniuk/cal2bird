<?php

namespace CalBird\Tests;

use CalBird\CalendarToTimesheetConnector;
use CalBird\MoneyBird\TimeSheets;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

final class CalendarToTimesheetConnectorTest extends TestCase
{
    use ProphecyTrait;

    public function test_it_creates_no_time_entries_if_there_are_no_matching_events()
    {
        $source = new FakeAndEmptyCalendar();
        $destination = $this->prophesize(TimeSheets::class);
        $connector = new CalendarToTimesheetConnector($source, $destination->reveal());

        $connector->createMatchingTimeEntries("Project name");

        $destination->save(Argument::any())->shouldNotHaveBeenCalled();
    }
}
