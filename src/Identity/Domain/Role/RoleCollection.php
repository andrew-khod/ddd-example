<?php

declare(strict_types=1);

namespace App\Identity\Domain\Role;

use App\Shared\Domain\BaseCollection;

final class RoleCollection extends BaseCollection
{
    public function __construct(Role ...$roles)
    {
        parent::__construct(...$roles);
    }
}
