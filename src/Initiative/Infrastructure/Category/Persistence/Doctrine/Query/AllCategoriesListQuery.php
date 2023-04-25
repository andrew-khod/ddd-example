<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Category\Persistence\Doctrine\Query;

use App\Initiative\Application\Category\Query\AllCategoriesListQuery as AllCategoriesListQueryInterface;
use App\Initiative\Domain\Category\Category;
use App\Initiative\Domain\Category\CategoryCollection;
use App\Shared\Infrastructure\Doctrine\Query\SwitchableTenantBaseQuery;

//final class AllCategoriesListQuery extends TenantAwareBaseQuery implements AllCategoriesListQueryInterface
final class AllCategoriesListQuery extends SwitchableTenantBaseQuery implements AllCategoriesListQueryInterface
{
    protected function getClass(): string
    {
        return Category::class;
    }

    public function query(): CategoryCollection
    {
//        return new CategoryCollection(...$this->repository->findAll());
        return new CategoryCollection(
            ...$this->createQueryBuilder('category')
                    ->addSelect('translations')
                    ->addSelect('language')
                    ->join('category.translations', 'translations')
                    ->join('translations.language', 'language')
        //            ->andWhere('')
                    ->getQuery()->getResult()
        );
    }
}
