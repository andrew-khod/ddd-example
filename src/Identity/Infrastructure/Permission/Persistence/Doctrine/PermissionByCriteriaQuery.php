<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Permission\Persistence\Doctrine;

use App\Identity\Application\Permission\Query\PermissionByCriteriaQuery as PermissionByCriteriaQueryInterface;
use App\Identity\Application\Permission\Query\PermissionCriteria;
use App\Identity\Domain\Permission\Permission;
use App\Identity\Domain\Permission\PermissionCollection;
use App\Shared\Infrastructure\Doctrine\Query\BaseQuery;

final class PermissionByCriteriaQuery extends BaseQuery implements PermissionByCriteriaQueryInterface
{
    protected function getClass(): string
    {
        return Permission::class;
    }

    public function queryMultiple(PermissionCriteria $criteria): PermissionCollection
    {
        return new PermissionCollection(...$this->findMultipleByCriteria($criteria));
    }
}
