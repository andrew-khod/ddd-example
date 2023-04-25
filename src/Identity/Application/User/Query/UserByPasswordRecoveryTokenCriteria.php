<?php

declare(strict_types=1);

namespace App\Identity\Application\User\Query;

use Doctrine\ORM\Query\Expr\Comparison;

final class UserByPasswordRecoveryTokenCriteria implements UserCriteria
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function toArray(): array
    {
        return [
            'password_recovery_token' => $this->token,
        ];
    }

    public function operatorFor(string $field): ?string
    {
        return Comparison::EQ;
    }
}
