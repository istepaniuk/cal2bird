<?php

namespace CalBird\Calendar;

use DateTimeInterface;

interface Calendar
{
    public function events(DateTimeInterface $from): Events;
}
