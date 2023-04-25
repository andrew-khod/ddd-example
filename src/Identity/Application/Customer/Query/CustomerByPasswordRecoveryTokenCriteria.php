<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\Query;

use Doctrine\ORM\Query\Expr\Comparison;

final class CustomerByPasswordRecoveryTokenCriteria implements CustomerCriteria
{
    private string $passwordRecoveryToken;

    public function __construct(string $passwordRecoveryToken)
    {
        $this->passwordRecoveryToken = $passwordRecoveryToken;
    }

    public function toArray(): array
    {
        return [
            'password_recovery_token' => $this->passwordRecoveryToken,
        ];
    }

    public function operatorFor(string $field): ?string
    {
        return Comparison::EQ;
    }
}
