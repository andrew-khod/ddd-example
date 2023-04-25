<?php

declare(strict_types=1);

namespace App\Identity\Application\Permission\Query;

use App\Identity\Domain\Permission\PermissionId;
use Doctrine\ORM\Query\Expr\Comparison;

final class PermissionByIdCriteria implements PermissionCriteria
{
    private array $permissions;

    public function __construct(array $permissions)
    {
        // TODO using array of EntityId is impossible with findBy(), find the proper way
        $this->permissions = array_map(function (string $permission) {
            return (new PermissionId($permission))->toBinary();
        }, $permissions);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->permissions,
        ];
    }

    public function operatorFor(string $field): ?string
    {
        return Comparison::EQ;
    }
}
