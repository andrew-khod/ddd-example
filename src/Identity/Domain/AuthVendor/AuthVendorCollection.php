<?php

declare(strict_types=1);

namespace App\Identity\Domain\AuthVendor;

use App\Shared\Domain\BaseCollection;

final class AuthVendorCollection extends BaseCollection
{
    public function __construct(AuthVendor ...$images)
    {
        parent::__construct(...$images);
    }
}
