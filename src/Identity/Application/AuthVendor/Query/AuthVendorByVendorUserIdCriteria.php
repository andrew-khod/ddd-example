<?php

declare(strict_types=1);

namespace App\Identity\Application\AuthVendor\Query;

use Doctrine\ORM\Query\Expr\Comparison;

final class AuthVendorByVendorUserIdCriteria implements AuthVendorCriteria
{
    private string $vendorUserId;

    public function __construct(string $vendorUserId)
    {
        $this->vendorUserId = $vendorUserId;
    }

    public function toArray(): array
    {
        return [
            'vendor_user_id' => $this->vendorUserId,
        ];
    }

    public function operatorFor(string $field): ?string
    {
        return Comparison::EQ;
    }
}
