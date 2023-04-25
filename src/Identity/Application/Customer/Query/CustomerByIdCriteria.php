<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\Query;

use App\Identity\Domain\Customer\CustomerId;
use Doctrine\ORM\Query\Expr\Comparison;

final class CustomerByIdCriteria implements CustomerCriteria
{
    private CustomerId $id;

    public function __construct(CustomerId $id)
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
