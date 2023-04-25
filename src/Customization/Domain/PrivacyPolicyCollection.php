<?php

declare(strict_types=1);

namespace App\Customization\Domain;

use App\Shared\Domain\BaseCollection;

class PrivacyPolicyCollection extends BaseCollection
{
    public function __construct(PrivacyPolicy ...$policies)
    {
        parent::__construct(...$policies);
    }
}
