<?php

declare(strict_types=1);

namespace App\Identity\Application\Permission\Query;

use Doctrine\ORM\Query\Expr\Comparison;

final class PermissionByNameCriteria implements PermissionCriteria
{
    private array $names;

    public function __construct(array $names)
    {
        $this->names = $names;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->names,
        ];
    }

    public function operatorFor(string $field): ?string
    {
        return Comparison::EQ;
    }
}
