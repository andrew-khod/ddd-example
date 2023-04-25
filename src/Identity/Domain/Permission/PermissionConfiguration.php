<?php

declare(strict_types=1);

namespace App\Identity\Domain\Permission;

use App\Identity\Domain\Role\RoleCollection;

final class PermissionConfiguration
{
    private PermissionCollection $permissions;
    private RoleCollection $roles;

    public function __construct(PermissionCollection $permissions, RoleCollection $roles)
    {
        $this->permissions = $permissions;
        $this->roles = $roles;
    }

    public function permissions(): PermissionCollection
    {
        return $this->permissions;
    }

    public function roles(): RoleCollection
    {
        return $this->roles;
    }
}
