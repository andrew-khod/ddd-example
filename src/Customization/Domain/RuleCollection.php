<?php

declare(strict_types=1);

namespace App\Customization\Domain;

use App\Shared\Domain\BaseCollection;

class RuleCollection extends BaseCollection
{
    public function __construct(Rule ...$rules)
    {
        parent::__construct(...$rules);
    }
}
