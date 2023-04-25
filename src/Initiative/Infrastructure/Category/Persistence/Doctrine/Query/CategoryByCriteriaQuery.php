<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Category\Persistence\Doctrine\Query;

use App\Initiative\Application\Category\Query\CategoryByCriteriaQuery as CategoryByCriteriaQueryInterface;
use App\Initiative\Application\Category\Query\CategoryCriteria;
use App\Initiative\Domain\Category\Category;
use App\Initiative\Domain\Category\CategoryCollection;
use App\Shared\Infrastructure\Doctrine\Query\SwitchableTenantBaseQuery;

//class CategoryByCriteriaQuery extends TenantAwareBaseQuery implements CategoryByCriteriaQueryInterface
class CategoryByCriteriaQuery extends SwitchableTenantBaseQuery implements CategoryByCriteriaQueryInterface
{
    protected function getClass(): string
    {
        return Category::class;
    }

    public function queryOne(CategoryCriteria $criteria): ?Category
    {
        return $this->findOneByCriteria($criteria);
    }

    public function queryMultiple(CategoryCriteria $criteria): CategoryCollection
    {
        return new CategoryCollection(...$this->findMultipleByCriteria($criteria));
    }
}
