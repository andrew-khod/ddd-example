<?php

namespace App\Customization\Application;

use App\Customization\Domain\CookiesPolicy;

interface CookiesPolicyEntityManager
{
    public function create(CookiesPolicy $policy): void;
    public function flush(): void;
    public function deleteAll(): void;
}