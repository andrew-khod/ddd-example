<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Initiative;

final class InitiativeStatistics
{
    private int $initiativesAmount;
    private int $lastWeekInitiatives;
    private int $lastWeekComments;
    private int $totalShares;
    private int $commentsAmount;

    public function __construct(int $initiativesAmount,
                                int $lastWeekInitiatives,
                                int $totalShares,
                                int $commentsAmount,
                                int $lastWeekComments)
    {
        $this->initiativesAmount = $initiativesAmount;
        $this->totalShares = $totalShares;
        $this->commentsAmount = $commentsAmount;
        $this->lastWeekInitiatives = $lastWeekInitiatives;
        $this->lastWeekComments = $lastWeekComments;
    }

    public function initiatives(): int
    {
        return $this->initiativesAmount;
    }

    public function lastWeekInitiatives(): int
    {
        return $this->lastWeekInitiatives;
    }

    public function lastWeekComments(): int
    {
        return $this->lastWeekComments;
    }

    public function comments(): int
    {
        return $this->commentsAmount;
    }
}