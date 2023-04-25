<?php

declare(strict_types=1);

namespace App\Identity\Application\AuthVendor\Query;

use App\Identity\Domain\User\UserId;
use Doctrine\ORM\Query\Expr\Comparison;

final class AuthVendorByUserIdCriteria implements AuthVendorCriteria
{
    private UserId $userId;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
        ];
    }

    public function operatorFor(string $field): ?string
    {
        return Comparison::EQ;
    }
}
