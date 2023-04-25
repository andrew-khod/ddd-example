<?php

namespace App\Customization\Application\Query;

use App\Customization\Domain\ContactCollection;

interface ContactByCriteriaQuery
{
//    public function queryOne(ContactCriteria $criteria): ?Contact;
    public function queryMultiple(ContactCriteria $criteria): ContactCollection;
}
