<?php

declare(strict_types=1);

namespace App\Shared\Domain;

abstract class BaseCollection
{
    protected array $items;

    public function __construct(...$items)
    {
        $this->items = $items;
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count($this->items);
    }
}
