<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\UseCase\FavouriteInitiative;

final class FavouriteInitiativeCommand
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
