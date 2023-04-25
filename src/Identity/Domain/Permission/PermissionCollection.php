<?php

declare(strict_types=1);

namespace App\Identity\Domain\Permission;

use App\Shared\Domain\BaseCollection;

final class PermissionCollection extends BaseCollection
{
    public function __construct(Permission ...$permissions)
    {
        parent::__construct(...$permissions);
    }

    public function toIDs(bool $stringify = false): array
    {
        return array_map(
            fn(Permission $permission) => $stringify
                ? (string) $permission->id()
                : $permission->id(),
            $this->items
        );
    }
}
