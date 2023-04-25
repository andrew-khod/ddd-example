<?php

declare(strict_types=1);

namespace App\Initiative\Application\Category\UseCase\UpdateCategories;

final class UpdateCategoriesCommand
{
    private array $categories;
    private array $toRemove;
    private ?string $toBackup;

    public function __construct(array $categories, array $toRemove, ?string $toBackup = null)
    {
        $this->categories = $categories;
        $this->toRemove = $toRemove;
        $this->toBackup = $toBackup;
    }

    public function toUpdate(): array
    {
        // Uuid::isValid($id)
        return array_filter($this->categories, fn(string $id) => !preg_match('/new_\d+/', $id), ARRAY_FILTER_USE_KEY);
    }

    public function toAdd(): array
    {
        return array_filter($this->categories, fn(string $id) => preg_match('/new_\d+/', $id), ARRAY_FILTER_USE_KEY);
    }

    public function toRemove(): array
    {
        return $this->toRemove;
//        return array_filter($this->categories, fn(string $id) => preg_match('new_\d+', $id));
    }

    public function toBackup(): ?string
    {
        return $this->toBackup;
    }
}
