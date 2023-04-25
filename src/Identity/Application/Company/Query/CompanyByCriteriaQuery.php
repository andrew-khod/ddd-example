<?php

namespace App\Identity\Application\Company\Query;

use App\Identity\Domain\Company\Company;
use App\Identity\Domain\Company\CompanyCollection;

interface CompanyByCriteriaQuery
{
    public function queryOne(CompanyCriteria $criteria): ?Company;

    public function queryMultiple(CompanyCriteria $criteria): CompanyCollection;
}
