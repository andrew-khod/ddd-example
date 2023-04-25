<?php

declare(strict_types=1);

namespace App\Customization\Domain;

use App\Shared\Domain\BaseCollection;

class ContactCollection extends BaseCollection
{
    public function __construct(Contact ...$contacts)
    {
        parent::__construct(...$contacts);
    }
}
