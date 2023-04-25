<?php

declare(strict_types=1);

namespace App\Customization\Domain;

use App\Shared\Domain\BaseCollection;

class QuestionCollection extends BaseCollection
{
    public function __construct(Question ...$questions)
    {
        parent::__construct(...$questions);
    }
}
