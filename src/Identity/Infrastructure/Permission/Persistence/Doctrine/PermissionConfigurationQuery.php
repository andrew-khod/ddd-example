<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Permission\Persistence\Doctrine;

use App\Identity\Application\Permission\Query\PermissionConfigurationQuery as PermissionConfigurationQueryInterface;
use App\Identity\Domain\Permission\PermissionConfiguration;
use App\Identity\Infrastructure\Role\Persistence\Doctrine\AllRoleListQuery;

final class PermissionConfigurationQuery implements PermissionConfigurationQueryInterface
{
    private AllPermissionListQuery $allPermissionListQuery;

    private AllRoleListQuery $allRoleListQuery;

    public function __construct(AllPermissionListQuery $allPermissionListQuery,
                                AllRoleListQuery $allRoleListQuery)
    {
        $this->allPermissionListQuery = $allPermissionListQuery;
        $this->allRoleListQuery = $allRoleListQuery;
    }

    public function query(): PermissionConfiguration
    {
        $permissions = $this->allPermissionListQuery->query();
        $roles = $this->allRoleListQuery->query();

        return new PermissionConfiguration($permissions, $roles);
    }
}
