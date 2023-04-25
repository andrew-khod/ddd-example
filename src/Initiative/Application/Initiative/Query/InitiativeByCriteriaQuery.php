<?php

namespace App\Initiative\Application\Initiative\Query;

use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Initiative\InitiativeCollection;

interface InitiativeByCriteriaQuery
{
    public function queryOne(InitiativeCriteria $criteria): ?Initiative;

    public function queryMultipleV2(InitiativeCriteria $criteria): InitiativeCollection;

    public function queryMultiple(InitiativeCriteria $criteria): InitiativeCollection;

    public function queryMultipleByPagination(InitiativeByFilterCriteria $criteria);
}
