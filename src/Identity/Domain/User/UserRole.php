<?php

declare(strict_types=1);

namespace App\Identity\Domain\User;

use App\Identity\Domain\Role\RoleId;

class UserRole
{
    private RoleId $role_id;

    private BaseUser $user;

    public function id(): RoleId
    {
        return $this->role_id;
    }
}
