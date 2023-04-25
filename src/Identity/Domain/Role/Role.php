<?php

declare(strict_types=1);

namespace App\Identity\Domain\Role;

use App\Identity\Domain\Permission\PermissionCollection;
use App\Shared\Domain\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Role implements Entity
{
    private RoleId $id;

    private string $name;

    private Collection $permissions;

    public function __construct(string $role, PermissionCollection $permissions)
    {
        $this->name = $role;
        $this->permissions = new ArrayCollection($permissions->toArray());
    }

    public function id(): RoleId
    {
        return $this->id;
    }

    public function role(): string
    {
        return $this->name;
    }

    public function permissions(): PermissionCollection
    {
        return new PermissionCollection(...$this->permissions);
    }
}
