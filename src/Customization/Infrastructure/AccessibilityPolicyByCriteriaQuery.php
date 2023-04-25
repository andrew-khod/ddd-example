<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\Query\AccessibilityPolicyByCriteriaQuery as AccessibilityPolicyByCriteriaQueryAlias;
use App\Customization\Application\Query\AccessibilityPolicyCriteria;
use App\Customization\Domain\AccessibilityPolicy;
use App\Customization\Domain\AccessibilityPolicyCollection;
use App\Shared\Infrastructure\Doctrine\Query\SwitchableTenantBaseQuery;

class AccessibilityPolicyByCriteriaQuery extends SwitchableTenantBaseQuery implements AccessibilityPolicyByCriteriaQueryAlias
{
    protected function getClass(): string
    {
        return AccessibilityPolicy::class;
    }

//    public function queryOne(QuestionCriteria $criteria): ?Question
//    {
//        return $this->findOneByCriteria($criteria);
//    }

    public function queryMultiple(AccessibilityPolicyCriteria $criteria): AccessibilityPolicyCollection
    {
        return new AccessibilityPolicyCollection(...$this->findMultipleByCriteriaV2($criteria));
    }
}