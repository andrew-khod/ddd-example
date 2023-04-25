<?php

namespace App\Customization\Application\Query;

interface TermsOfUseByCriteriaQuery
{
//    public function queryOne(ContactCriteria $criteria): ?Contact;
    public function queryMultiple(TermsOfUseCriteria $criteria);
//    public function queryMultiple(RuleCriteria $criteria): RuleCollection;
}
