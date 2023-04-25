<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\Query;

use App\Identity\Domain\Customer\Username;
use Doctrine\ORM\Query\Expr\Comparison;

final class CustomerByUsernameCriteria implements CustomerCriteria
{
    private Username $username;

    public function __construct(Username $username)
    {
        $this->username = $username;
    }

    public function toArray(): array
    {
        return [
            'username' => $this->username,
        ];
    }

    public function operatorFor(string $field): ?string
    {
        return Comparison::EQ;
    }
}
