<?php

declare(strict_types=1);

namespace App\Customization\Domain;

use App\Shared\Domain\BaseCollection;

class AccessibilityPolicyCollection extends BaseCollection
{
    public function __construct(AccessibilityPolicy ...$policies)
    {
        parent::__construct(...$policies);
    }
}
