<?php

namespace App\Customization\Application\Query;

interface RuleByCriteriaQuery
{
//    public function queryOne(ContactCriteria $criteria): ?Contact;
    public function queryMultiple(RuleCriteria $criteria);
//    public function queryMultiple(RuleCriteria $criteria): RuleCollection;
}
