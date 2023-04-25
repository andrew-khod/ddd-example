<?php

namespace App\Customization\Application;

use App\Customization\Domain\AccessibilityPolicy;

interface AccessibilityPolicyEntityManager
{
    public function create(AccessibilityPolicy $policy): void;
    public function flush(): void;
    public function deleteAll(): void;
}