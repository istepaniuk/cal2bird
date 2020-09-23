<?php

namespace CalBird\Calendar;

final class EventId
{
    private string $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    public function __toString()
    {
        return $this->id;
    }
}
