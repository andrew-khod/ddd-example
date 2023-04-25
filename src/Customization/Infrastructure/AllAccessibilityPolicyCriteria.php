<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\Query\AccessibilityPolicyCriteria;
use Happyr\DoctrineSpecification\Specification\BaseSpecification;

class AllAccessibilityPolicyCriteria extends BaseSpecification implements AccessibilityPolicyCriteria
{
    protected function getSpec()
    {
        return [];
    }
}