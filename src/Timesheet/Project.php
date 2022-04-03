<?php

namespace CalBird\Timesheet;

final class Project
{
    private string $project;

    private function __construct(string $project)
    {
        $this->project = $project;
    }

    public static function fromString(string $project): self
    {
        return new self($project);
    }

    public static function none(): self
    {
        return new self('');
    }

    public function isNone(): bool
    {
        return empty($this->project);
    }

    public function __toString(): string
    {
        return $this->project;
    }
}
