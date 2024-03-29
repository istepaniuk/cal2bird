<?php

namespace CalBird\Timesheet;

final class Description
{
    private string $description;

    private function __construct(string $description)
    {
        $this->description = $description;
    }

    public static function fromString(string $description): self
    {
        if(empty($description)){
            throw new \InvalidArgumentException('Description cannot be empty');
        }

        return new self($description);
    }

    public function __toString(): string
    {
        return $this->description;
    }
}
