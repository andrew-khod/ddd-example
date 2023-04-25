<?php

namespace App\Customization\Application\Query;

interface PrivacyPolicyByCriteriaQuery
{
//    public function queryOne(ContactCriteria $criteria): ?Contact;
    public function queryMultiple(PrivacyPolicyCriteria $criteria);
//    public function queryMultiple(RuleCriteria $criteria): RuleCollection;
}
