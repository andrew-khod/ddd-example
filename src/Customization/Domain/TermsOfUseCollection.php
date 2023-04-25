<?php

declare(strict_types=1);

namespace App\Customization\Domain;

use App\Shared\Domain\BaseCollection;

class TermsOfUseCollection extends BaseCollection
{
    public function __construct(TermsOfUse ...$terms)
    {
        parent::__construct(...$terms);
    }
}
