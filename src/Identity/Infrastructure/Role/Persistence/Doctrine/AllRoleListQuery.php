<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Role\Persistence\Doctrine;

use App\Identity\Domain\Role\Role;
use App\Identity\Domain\Role\RoleCollection;
use App\Shared\Infrastructure\Doctrine\Query\BaseQuery;

final class AllRoleListQuery extends BaseQuery
{
    protected function getClass(): string
    {
        return Role::class;
    }

    public function query(): RoleCollection
    {
        return new RoleCollection(...$this->repository->findAll());
    }
}
