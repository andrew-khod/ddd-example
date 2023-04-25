<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\Query\TermsOfUseCriteria;
use Happyr\DoctrineSpecification\Specification\BaseSpecification;

class AllTermsOfUseCriteria extends BaseSpecification implements TermsOfUseCriteria
{
    protected function getSpec()
    {
        return [];
    }
}