<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Permission\Persistence\Doctrine;

use App\Identity\Domain\Permission\Permission;
use App\Identity\Domain\Permission\PermissionCollection;
use App\Shared\Infrastructure\Doctrine\Query\BaseQuery;

final class AllPermissionListQuery extends BaseQuery
{
    protected function getClass(): string
    {
        return Permission::class;
    }

    public function query(): PermissionCollection
    {
        return new PermissionCollection(...$this->repository->findAll());
    }
}
