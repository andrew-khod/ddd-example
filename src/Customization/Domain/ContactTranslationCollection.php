<?php

declare(strict_types=1);

namespace App\Customization\Domain;

use App\Shared\Domain\BaseCollection;

class ContactTranslationCollection extends BaseCollection
{
    public function __construct(ContactTranslation ...$translations)
    {
        parent::__construct(...$translations);
    }
}
