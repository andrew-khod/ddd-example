<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\UseCase\UnfollowInitiative;

final class UnfollowInitiativeCommand
{
    private string $initiative;

    public function __construct(string $initiative)
    {
        $this->initiative = $initiative;
    }

    public function initiative(): string
    {
        return $this->initiative;
    }
}
