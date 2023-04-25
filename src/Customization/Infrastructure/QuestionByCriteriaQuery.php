<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\Query\QuestionByCriteriaQuery as QuestionByCriteriaQueryInterface;
use App\Customization\Application\Query\QuestionCriteria;
use App\Customization\Domain\Question;
use App\Customization\Domain\QuestionCollection;
use App\Shared\Infrastructure\Doctrine\Query\SwitchableTenantBaseQuery;

class QuestionByCriteriaQuery extends SwitchableTenantBaseQuery implements QuestionByCriteriaQueryInterface
{
    protected function getClass(): string
    {
        return Question::class;
    }

//    public function queryOne(QuestionCriteria $criteria): ?Question
//    {
//        return $this->findOneByCriteria($criteria);
//    }

    public function queryMultiple(QuestionCriteria $criteria): QuestionCollection
    {
        return new QuestionCollection(...$this->findMultipleByCriteriaV2($criteria));
    }
}