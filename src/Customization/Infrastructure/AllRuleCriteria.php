<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\Query\RuleCriteria;
use Happyr\DoctrineSpecification\Specification\BaseSpecification;

class AllRuleCriteria extends BaseSpecification implements RuleCriteria
{
    protected function getSpec()
    {
        return [];
    }
}