<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Initiative;

class Location
{
    public const REGEX = '/([0-9]+.[0-9]+).+?([0-9]+.[0-9]+)/';
    private string $location;
    private ?float $radiusValue;
    private ?string $radiusUnit;
    private string $name;

    public function __construct(string $location,
                                string $name,
                                ?float $radiusValue = null,
                                ?string $radiusUnit = null)
    {
        $this->location = $location;
        $this->name = $name;
        $this->radiusValue = $radiusValue;
        $this->radiusUnit = $radiusUnit;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function latitude(): float
    {
        return (float) $this->parsed()[0];
    }

    public function longitude(): float
    {
        return (float) $this->parsed()[1];
    }

    public function radiusValue(): ?float
    {
        return $this->radiusValue;
    }

    public function radiusUnit(): ?string
    {
        return $this->radiusUnit;
    }

    private function parsed(): array
    {
        // TODO move check to constructor
        preg_match(self::REGEX, $this->location, $matches);
        unset($matches[0]);

        return array_values($matches);
    }

    public function relocate(string $coordinates, string $locationName): self
    {
        return new self($coordinates, $locationName, $this->radiusValue, $this->radiusUnit);
    }

    public function updateRadius(?float $locationRadiusValue): self
    {
        return new self($this->location, $this->name, $locationRadiusValue, $this->radiusUnit);
    }

    public function updateRadiusUnit(string $locationRadiusUnit): self
    {
        return new self($this->location, $this->name, $this->radiusValue, $locationRadiusUnit);
    }

    public function __toString(): string
    {
        return sprintf('%f, %f', $this->latitude(), $this->longitude());
    }
}
