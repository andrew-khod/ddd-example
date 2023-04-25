<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\Query\ContactByCriteriaQuery as ContactByCriteriaQueryInterface;
use App\Customization\Application\Query\ContactCriteria;
use App\Customization\Domain\Contact;
use App\Customization\Domain\ContactCollection;
use App\Shared\Infrastructure\Doctrine\Query\SwitchableTenantBaseQuery;

class ContactByCriteriaQuery extends SwitchableTenantBaseQuery implements ContactByCriteriaQueryInterface
{
    protected function getClass(): string
    {
        return Contact::class;
    }

//    public function queryOne(QuestionCriteria $criteria): ?Question
//    {
//        return $this->findOneByCriteria($criteria);
//    }

    public function queryMultiple(ContactCriteria $criteria): ContactCollection
    {
        return new ContactCollection(...$this->findMultipleByCriteriaV2($criteria));
    }
}