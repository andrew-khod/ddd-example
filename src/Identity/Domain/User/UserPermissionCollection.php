<?php

declare(strict_types=1);

namespace App\Identity\Domain\User;

use App\Shared\Domain\BaseCollection;

final class UserPermissionCollection extends BaseCollection
{
    public function __construct(UserPermission ...$permissions)
    {
        parent::__construct(...$permissions);
    }
}
