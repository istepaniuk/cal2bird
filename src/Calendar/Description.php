<?php

namespace CalBird\Calendar;

final class Description
{
    private string $description;

    private function __construct(string $description)
    {
        $this->description = $description;
    }

    public static function fromString(string $description): self
    {
        return new self($description);
    }

    public function matches(self $description): bool
    {
        return $description->description == $this->description;
    }
}
