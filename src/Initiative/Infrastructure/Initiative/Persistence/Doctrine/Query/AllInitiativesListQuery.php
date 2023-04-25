<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Initiative\Persistence\Doctrine\Query;

use App\Initiative\Application\Initiative\Query\AllInitiativesListQuery as AllInitiativesListQueryInterface;
use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Initiative\InitiativeCollection;
use App\Shared\Infrastructure\Doctrine\Query\TenantAwareBaseQuery;

class AllInitiativesListQuery extends TenantAwareBaseQuery implements AllInitiativesListQueryInterface
{
    protected function getClass(): string
    {
        return Initiative::class;
    }

    public function query(): InitiativeCollection
    {
        return new InitiativeCollection(...$this->repository->findBy([], [
            'created' => 'DESC',
        ]));
    }
}
