<?php

declare(strict_types=1);

namespace App\Identity\Domain\Permission;

use App\Shared\Domain\Entity;

class Permission implements Entity
{
    private PermissionId $id;

    private string $name;

    public function __construct(string $permission)
    {
        $this->name = $permission;
    }

    public function permission(): string
    {
        return $this->name;
    }

    public function id(): PermissionId
    {
        return $this->id;
    }
}
