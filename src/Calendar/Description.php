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

    public static function empty(): self
    {
        return new self('');
    }

    public function __toString(): string
    {
        return $this->description;
    }

    public function isEmpty(): bool
    {
        return empty($this->description);
    }
}
