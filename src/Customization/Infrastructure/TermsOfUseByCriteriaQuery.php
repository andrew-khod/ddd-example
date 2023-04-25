<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\Query\TermsOfUseByCriteriaQuery as TermsOfUseByCriteriaQueryAlias;
use App\Customization\Application\Query\TermsOfUseCriteria;
use App\Customization\Domain\TermsOfUse;
use App\Customization\Domain\TermsOfUseCollection;
use App\Shared\Infrastructure\Doctrine\Query\SwitchableTenantBaseQuery;

class TermsOfUseByCriteriaQuery extends SwitchableTenantBaseQuery implements TermsOfUseByCriteriaQueryAlias
{
    protected function getClass(): string
    {
        return TermsOfUse::class;
    }

//    public function queryOne(QuestionCriteria $criteria): ?Question
//    {
//        return $this->findOneByCriteria($criteria);
//    }

    public function queryMultiple(TermsOfUseCriteria $criteria): TermsOfUseCollection
    {
        return new TermsOfUseCollection(...$this->findMultipleByCriteriaV2($criteria));
    }
}