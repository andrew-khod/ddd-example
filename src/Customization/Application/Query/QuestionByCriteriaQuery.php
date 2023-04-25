<?php

namespace App\Customization\Application\Query;

use App\Customization\Domain\QuestionCollection;

interface QuestionByCriteriaQuery
{
//    public function queryOne(QuestionCriteria $criteria): ?Question;
    public function queryMultiple(QuestionCriteria $criteria): QuestionCollection;
}
