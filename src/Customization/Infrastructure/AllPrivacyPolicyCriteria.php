<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\Query\PrivacyPolicyCriteria;
use Happyr\DoctrineSpecification\Specification\BaseSpecification;

class AllPrivacyPolicyCriteria extends BaseSpecification implements PrivacyPolicyCriteria
{
    protected function getSpec()
    {
        return [];
    }
}