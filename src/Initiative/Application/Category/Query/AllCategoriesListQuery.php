<?php

namespace App\Initiative\Application\Category\Query;

use App\Initiative\Domain\Category\CategoryCollection;

interface AllCategoriesListQuery
{
    public function query(): CategoryCollection;
}
