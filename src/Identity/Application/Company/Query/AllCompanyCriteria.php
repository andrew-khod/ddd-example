<?php

declare(strict_types=1);

namespace App\Identity\Application\Company\Query;

use Doctrine\ORM\Query\Expr\Comparison;

final class AllCompanyCriteria implements CompanyCriteria
{
    public function toArray(): array
    {
        return [];
    }

    public function operatorFor(string $field): ?string
    {
        return Comparison::EQ;
    }
}
