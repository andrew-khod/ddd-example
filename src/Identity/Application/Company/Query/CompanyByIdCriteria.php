<?php

declare(strict_types=1);

namespace App\Identity\Application\Company\Query;

use App\Identity\Domain\Company\CompanyId;
use Doctrine\ORM\Query\Expr\Comparison;

final class CompanyByIdCriteria implements CompanyCriteria
{
    private CompanyId $id;

    public function __construct(CompanyId $id)
    {
        $this->id = $id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->toBinary(),
        ];
    }

    public function operatorFor(string $field): ?string
    {
        return Comparison::EQ;
    }
}
