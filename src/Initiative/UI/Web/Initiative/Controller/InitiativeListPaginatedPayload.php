<?php

declare(strict_types=1);

namespace App\Initiative\UI\Web\Initiative\Controller;

final class InitiativeListPaginatedPayload
{
    private array $initiatives;

    public function __construct(array $initiatives)
    {
        $this->initiatives = $initiatives;
    }

    public function initiatives(): array
    {
        return $this->initiatives;
    }
}