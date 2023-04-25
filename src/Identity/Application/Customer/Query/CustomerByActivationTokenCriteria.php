<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\Query;

use Doctrine\ORM\Query\Expr\Comparison;

final class CustomerByActivationTokenCriteria implements CustomerCriteria
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function toArray(): array
    {
        return [
            'activation_token' => $this->token,
        ];
    }

    public function operatorFor(string $field): ?string
    {
        return Comparison::EQ;
    }
}
