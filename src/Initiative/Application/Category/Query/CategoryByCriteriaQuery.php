<?php

namespace App\Initiative\Application\Category\Query;

use App\Initiative\Domain\Category\Category;
use App\Initiative\Domain\Category\CategoryCollection;

interface CategoryByCriteriaQuery
{
    public function queryOne(CategoryCriteria $criteria): ?Category;

    public function queryMultiple(CategoryCriteria $criteria): CategoryCollection;
}
