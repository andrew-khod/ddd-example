<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\Query\QuestionCriteria;
use Happyr\DoctrineSpecification\Spec;
use Happyr\DoctrineSpecification\Specification\BaseSpecification;

class AllQuestionCriteria extends BaseSpecification implements QuestionCriteria
{
    protected function getSpec()
    {
        return Spec::andX(
            Spec::addSelect(Spec::selectEntity('translations')),
            Spec::join('translations', 't'),
            Spec::orderBy('orderIndex', 'ASC'),
        );
//        return [];
    }
}