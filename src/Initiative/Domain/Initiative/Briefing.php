<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Initiative;

class Briefing
{
    private string $type;
    private string $title;
    private string $description;
    private ?int $minimalJoinedPeople;

    public function __construct(string $type,
                                string $title,
                                string $description,
                                ?int $minimalJoinedPeople)
    {
        $this->type = $type;
        $this->title = $title;
        $this->description = $description;
        $this->minimalJoinedPeople = $minimalJoinedPeople;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function minimalJoinedPeople(): ?int
    {
        return $this->minimalJoinedPeople;
    }

    public function rename(string $title): self
    {
        return new self(
            $this->type,
            $title,
            $this->description,
            $this->minimalJoinedPeople
        );
    }

    public function migrateTo(string $type): self
    {
        return new self(
            $type,
            $this->title,
            $this->description,
            $this->minimalJoinedPeople
        );
    }

    public function updateContent(string $description): self
    {
        return new self(
            $this->type,
            $this->title,
            $description,
            $this->minimalJoinedPeople
        );
    }

    public function requirePeople(?int $minimalJoinedPeople): self
    {
        return new self(
            $this->type,
            $this->title,
            $this->description,
            $minimalJoinedPeople
        );
    }
}
