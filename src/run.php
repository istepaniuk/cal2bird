#!/usr/bin/env php
<?php

namespace CalBird;

use CalBird\Calendar\Summary;
use DateTimeImmutable;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;

require \dirname(__DIR__).'/vendor/autoload.php';

$logger = new ConsoleLogger(new ConsoleOutput(ConsoleOutput::VERBOSITY_DEBUG));
$connector = new CalendarToTimesheetConnector(
    new ICalendarFileCalendar('php://stdin'),
    new MoneybirdTimesheet($argv[1], $logger)
);

if ($argv[4] ?? "" == "--non-billable") {
    $connector->createAllTimeEntriesIntoNonBillable(new DateTimeImmutable($argv[3]));
} else {
    $connector->createMatchingTimeEntries(Summary::fromString($argv[2]), new DateTimeImmutable($argv[3]));
}

