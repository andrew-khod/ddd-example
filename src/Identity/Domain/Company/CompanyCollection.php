<?php

declare(strict_types=1);

namespace App\Identity\Domain\Company;

use App\Shared\Domain\BaseCollection;

final class CompanyCollection extends BaseCollection
{
    public function __construct(Company ...$companies)
    {
        parent::__construct(...$companies);
    }
}
