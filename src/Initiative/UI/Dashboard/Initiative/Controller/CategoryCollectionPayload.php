<?php

namespace App\Initiative\UI\Dashboard\Initiative\Controller;

use App\Initiative\Domain\Category\CategoryCollection;
use App\Shared\UI\DashboardPayload;

class CategoryCollectionPayload implements DashboardPayload
{
    private CategoryCollection $categories;

    public function __construct(CategoryCollection $categories)
    {
        $this->categories = $categories;
    }

    public function get(): CategoryCollection
    {
        return $this->categories;
    }
}