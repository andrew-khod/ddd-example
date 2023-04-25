<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\Query\ContactCriteria;
use Happyr\DoctrineSpecification\Spec;
use Happyr\DoctrineSpecification\Specification\BaseSpecification;

class AllContactCriteria extends BaseSpecification implements ContactCriteria
{
    protected function getSpec()
    {
        return Spec::andX(
            Spec::join('translations', 't'),
            Spec::orderBy('orderIndex', 'ASC'),
        );
//        return [];
    }
}