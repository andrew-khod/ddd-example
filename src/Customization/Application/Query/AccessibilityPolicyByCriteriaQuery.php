<?php

namespace App\Customization\Application\Query;

interface AccessibilityPolicyByCriteriaQuery
{
//    public function queryOne(ContactCriteria $criteria): ?Contact;
    public function queryMultiple(AccessibilityPolicyCriteria $criteria);
//    public function queryMultiple(RuleCriteria $criteria): RuleCollection;
}
