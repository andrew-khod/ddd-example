<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Initiative\Persistence\Doctrine\Query;

use App\Initiative\Application\Initiative\Query\InitiativeByCriteriaQuery as InitiativeByCriteriaQueryInterface;
use App\Initiative\Application\Initiative\Query\InitiativeByFilterCriteria;
use App\Initiative\Application\Initiative\Query\InitiativeCriteria;
use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Initiative\InitiativeCollection;
use App\Shared\Infrastructure\Doctrine\Query\SwitchableTenantBaseQuery;
use Doctrine\Common\Collections\Criteria;

class InitiativeByCriteriaQuery extends SwitchableTenantBaseQuery implements InitiativeByCriteriaQueryInterface
{
    protected function getClass(): string
    {
        return Initiative::class;
    }

//    public function queryOne(InitiativeCriteria $criteria): ?Initiative
//    {
//        return $this->findOneByCriteria($criteria);
//    }

    public function queryOne(InitiativeCriteria $criteria): ?Initiative
    {
        return $this->findOneByCriteriaV2($criteria);
    }

    public function queryMultipleV2(InitiativeCriteria $criteria): InitiativeCollection
    {
        return new InitiativeCollection(...$this->findMultipleByCriteriaV2($criteria));
    }

    public function queryMultiple(InitiativeCriteria $criteria): InitiativeCollection
    {
        $orderBy = sprintf('%s.created', self::ALIAS);

//        if ($criteria->sort()) {
//            $orderBy = $criteria->sort();
////            $orderBy = sprintf('%s.%s', self::ALIAS, $criteria->sort());
//        }

        return new InitiativeCollection(...$this->findMultipleByCriteria($criteria, $orderBy, Criteria::DESC, method_exists($criteria, 'page') ? $criteria->page() : 1));
    }

    public function queryMultipleByPagination(InitiativeByFilterCriteria $criteria)
    {
        $orderBy = sprintf('%s.created', self::ALIAS);

        // todo PaginationCollection
        $data = $this->findMultipleByCriteriaPagination($criteria, $orderBy, Criteria::DESC, method_exists($criteria, 'page') ? $criteria->page() : 1);
        $data['items'] = new InitiativeCollection(...$data['items']);
        return $data;
    }
}
