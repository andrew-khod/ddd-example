<?php

namespace App\Initiative\Application\Initiative\Query;

use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Initiative\InitiativeCollection;

interface InitiativeByCriteriaBaseQuery
{
    public function queryOne(InitiativeCriteria $criteria): ?Initiative;

    public function queryMultiple(InitiativeCriteria $criteria): InitiativeCollection;
}
