<?php

namespace CalBird\Tests;

use CalBird\Calendar\Calendar;
use CalBird\Calendar\Events;

final class FakeAndEmptyCalendar implements Calendar
{
    public function events(): Events
    {
        return Events::empty();
    }
}
