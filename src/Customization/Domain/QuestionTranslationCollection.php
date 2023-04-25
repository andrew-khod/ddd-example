<?php

declare(strict_types=1);

namespace App\Customization\Domain;

use App\Shared\Domain\BaseCollection;

class QuestionTranslationCollection extends BaseCollection
{
    public function __construct(QuestionTranslation ...$translations)
    {
        parent::__construct(...$translations);
    }
}
