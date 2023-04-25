<?php

namespace App\Customization\Application\Query;

interface CookiesPolicyByCriteriaQuery
{
//    public function queryOne(ContactCriteria $criteria): ?Contact;
    public function queryMultiple(CookiesPolicyCriteria $criteria);
//    public function queryMultiple(RuleCriteria $criteria): RuleCollection;
}
