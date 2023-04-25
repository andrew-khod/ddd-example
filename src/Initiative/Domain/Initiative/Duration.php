<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Initiative;

use DateTime;

class Duration
{
    private DateTime $dateStart;
    private DateTime $dateEnd;

    public function __construct(string $dateStart, string $dateEnd)
    {
        $this->dateStart = new DateTime($dateStart);
        $this->dateEnd = new DateTime($dateEnd);

        if ($this->dateStart > $this->dateEnd) {
            throw new InvalidDurationException();
        }
    }

    public function start(): DateTime
    {
        return $this->dateStart;
    }

    public function end(): DateTime
    {
        return $this->dateEnd;
    }

    public function rescheduleStarting(string $start): self
    {
        return new self($start, $this->dateEnd->format('Y-m-d H:i:s'));
    }

    public function rescheduleEnding(string $end): self
    {
        return new self($this->dateStart->format('Y-m-d H:i:s'), $end);
    }

    public function isExpired(): bool
    {
        return new DateTime() > $this->dateEnd;
    }

    public function __toString(): string
    {
        return sprintf('%s, %s', $this->dateStart->format('Y-m-d H:i:s'), $this->dateEnd->format('Y-m-d H:i:s'));
    }
}
