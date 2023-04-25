<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\Query;

use App\Shared\Application\AvailableLanguagesQuery as AvailableLanguagesQueryAlias;
use App\Shared\Domain\AvailableLanguage;

class AvailableLanguagesQuery extends BaseQuery implements AvailableLanguagesQueryAlias
{
    public function query(): array
    {
        return $this->repository->findAll();
    }

    protected function getClass(): string
    {
        return AvailableLanguage::class;
    }
}