<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\Query\CookiesPolicyCriteria;
use Happyr\DoctrineSpecification\Specification\BaseSpecification;

class AllCookiesPolicyCriteria extends BaseSpecification implements CookiesPolicyCriteria
{
    protected function getSpec()
    {
        return [];
    }
}