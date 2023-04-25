<?php

declare(strict_types=1);

namespace App\Customization\Domain;

use App\Shared\Domain\BaseCollection;

class CookiesPolicyCollection extends BaseCollection
{
    public function __construct(CookiesPolicy ...$policies)
    {
        parent::__construct(...$policies);
    }
}
