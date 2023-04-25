<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\Query;

use App\Identity\Domain\User\Email;
use Doctrine\ORM\Query\Expr\Comparison;

final class CustomerByEmailCriteria implements CustomerCriteria
{
    private Email $email;

    public function __construct(Email $email)
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
