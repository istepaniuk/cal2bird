<?php

namespace CalBird\Timesheet;

final class EntryId
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
