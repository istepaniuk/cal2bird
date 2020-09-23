<?php

namespace CalBird\Calendar;

final class EventId
{
    public static function fromString(string $string): self
    {
        return new self();
    }
}
