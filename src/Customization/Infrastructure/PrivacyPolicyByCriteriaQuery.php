<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\Query\PrivacyPolicyByCriteriaQuery as PrivacyPolicyByCriteriaQueryAlias;
use App\Customization\Application\Query\PrivacyPolicyCriteria;
use App\Customization\Domain\PrivacyPolicy;
use App\Customization\Domain\PrivacyPolicyCollection;
use App\Shared\Infrastructure\Doctrine\Query\SwitchableTenantBaseQuery;

class PrivacyPolicyByCriteriaQuery extends SwitchableTenantBaseQuery implements PrivacyPolicyByCriteriaQueryAlias
{
    protected function getClass(): string
    {
        return PrivacyPolicy::class;
    }

//    public function queryOne(QuestionCriteria $criteria): ?Question
//    {
//        return $this->findOneByCriteria($criteria);
//    }

    public function queryMultiple(PrivacyPolicyCriteria $criteria): PrivacyPolicyCollection
    {
        return new PrivacyPolicyCollection(...$this->findMultipleByCriteriaV2($criteria));
    }
}