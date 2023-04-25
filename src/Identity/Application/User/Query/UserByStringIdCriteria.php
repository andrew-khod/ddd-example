<?php

declare(strict_types=1);

namespace App\Identity\Application\User\Query;

use App\Shared\Domain\BaseId;
use Doctrine\ORM\Query\Expr\Comparison;

final class UserByStringIdCriteria implements UserCriteria
{
    private BaseId $id;

    public function __construct(BaseId $id)
    {
        $this->id = $id;
    }

    public function toArray(): array
    {
        return [
            'id' => (string) $this->id->toBinary(),
        ];
    }

    public function operatorFor(string $field): ?string
    {
        return Comparison::EQ;
    }
}
