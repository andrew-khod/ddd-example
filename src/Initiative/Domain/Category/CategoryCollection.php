<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Category;

use App\Shared\Domain\BaseCollection;

class CategoryCollection extends BaseCollection
{
    public function __construct(Category ...$categories)
    {
        parent::__construct(...$categories);
    }
}
