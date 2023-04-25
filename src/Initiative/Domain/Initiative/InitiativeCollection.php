<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Initiative;

use App\Shared\Domain\BaseCollection;

class InitiativeCollection extends BaseCollection
{
    public function __construct(Initiative ...$initiatives)
    {
        parent::__construct(...$initiatives);
    }

    public function toIDs(bool $stringify = false): array
    {
        return array_map(
            fn(Initiative $initiative) => $stringify
                ? (string) $initiative->id()
                : $initiative->id(),
            $this->items
        );
    }

    public function archived(): self
    {
        return new self(...array_filter(
            $this->items,
            fn(Initiative $initiative) => $initiative->isArchived(),
        ));
    }
}
