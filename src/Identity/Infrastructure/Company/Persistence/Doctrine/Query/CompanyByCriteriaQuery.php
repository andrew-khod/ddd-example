<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Company\Persistence\Doctrine\Query;

use App\Identity\Application\Company\Query\CompanyByCriteriaQuery as CompanyByCriteriaQueryInterface;
use App\Identity\Application\Company\Query\CompanyCriteria;
use App\Identity\Domain\Company\Company;
use App\Identity\Domain\Company\CompanyCollection;
use App\Shared\Infrastructure\Doctrine\Query\BaseQuery;

final class CompanyByCriteriaQuery extends BaseQuery implements CompanyByCriteriaQueryInterface
{
    protected function getClass(): string
    {
        return Company::class;
    }

    public function queryOne(CompanyCriteria $criteria): ?Company
    {
        return $this->findOneByCriteria($criteria);
    }

    public function queryMultiple(CompanyCriteria $criteria): CompanyCollection
    {
        return new CompanyCollection(...$this->findMultipleByCriteria($criteria));
    }
}
