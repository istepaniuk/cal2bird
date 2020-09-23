<?php

namespace CalBird\Calendar;

final class Summary
{
    private string $summary;

    private function __construct(string $summary)
    {
        $this->summary = $summary;
    }

    public static function fromString(string $summary): self
    {
        return new self($summary);
    }

    public function matches(self $summary): bool
    {
        return $summary->summary == $this->summary;
    }

    public function __toString()
    {
        return $this->summary;
    }
}
