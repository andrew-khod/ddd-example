<?php

namespace App\Customization\Application;

use App\Customization\Domain\Rule;

interface RuleEntityManager
{
    public function create(Rule $rule): void;
    public function flush(): void;
    public function deleteAll(): void;
}