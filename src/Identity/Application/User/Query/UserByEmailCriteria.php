<?php

declare(strict_types=1);

namespace App\Identity\Application\User\Query;

use Doctrine\ORM\Query\Expr\Comparison;

final class UserByEmailCriteria implements UserCriteria
{
    private string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
        ];
    }

    public function operatorFor(string $field): ?string
    {
        return Comparison::EQ;
    }
}
