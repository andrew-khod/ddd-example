<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\Query\RuleByCriteriaQuery as RuleByCriteriaQueryAlias;
use App\Customization\Application\Query\RuleCriteria;
use App\Customization\Domain\Rule;
use App\Customization\Domain\RuleCollection;
use App\Shared\Infrastructure\Doctrine\Query\SwitchableTenantBaseQuery;

class RuleByCriteriaQuery extends SwitchableTenantBaseQuery implements RuleByCriteriaQueryAlias
{
    protected function getClass(): string
    {
        return Rule::class;
    }

//    public function queryOne(QuestionCriteria $criteria): ?Question
//    {
//        return $this->findOneByCriteria($criteria);
//    }

    public function queryMultiple(RuleCriteria $criteria): RuleCollection
    {
        return new RuleCollection(...$this->findMultipleByCriteriaV2($criteria));
    }
}