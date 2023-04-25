<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Initiative\Persistence\Doctrine\Query;

use App\Initiative\Application\Initiative\Query\InitiativeByCriteriaBaseQuery as InitiativeByCriteriaBaseQueryAlias;
use App\Initiative\Application\Initiative\Query\InitiativeCriteria;
use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Initiative\InitiativeCollection;
use App\Shared\Infrastructure\Doctrine\Query\SwitchableTenantBaseQuery;
use Doctrine\Common\Collections\Criteria;

//class InitiativeByCriteriaBaseQuery extends SwitchableTenantBaseQuery implements InitiativeByCriteriaQueryInterface
class InitiativeByCriteriaBaseQuery extends SwitchableTenantBaseQuery implements InitiativeByCriteriaBaseQueryAlias
{
    protected function getClass(): string
    {
        return Initiative::class;
    }

    public function queryOne(InitiativeCriteria $criteria): ?Initiative
    {
        return $this->findOneByCriteria($criteria);
    }

    public function queryMultiple(InitiativeCriteria $criteria): InitiativeCollection
    {
        $orderBy = sprintf('%s.created', self::ALIAS);

//        if ($criteria->sort()) {
//            $orderBy = $criteria->sort();
////            $orderBy = sprintf('%s.%s', self::ALIAS, $criteria->sort());
//        }

        return new InitiativeCollection(...$this->findMultipleByCriteria($criteria, $orderBy, Criteria::DESC));
    }
}
