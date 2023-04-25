<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\Query\CookiesPolicyByCriteriaQuery as CookiesPolicyByCriteriaQueryAlias;
use App\Customization\Application\Query\CookiesPolicyCriteria;
use App\Customization\Domain\CookiesPolicy;
use App\Customization\Domain\CookiesPolicyCollection;
use App\Shared\Infrastructure\Doctrine\Query\SwitchableTenantBaseQuery;

class CookiesPolicyByCriteriaQuery extends SwitchableTenantBaseQuery implements CookiesPolicyByCriteriaQueryAlias
{
    protected function getClass(): string
    {
        return CookiesPolicy::class;
    }

//    public function queryOne(QuestionCriteria $criteria): ?Question
//    {
//        return $this->findOneByCriteria($criteria);
//    }

    public function queryMultiple(CookiesPolicyCriteria $criteria): CookiesPolicyCollection
    {
        return new CookiesPolicyCollection(...$this->findMultipleByCriteriaV2($criteria));
    }
}