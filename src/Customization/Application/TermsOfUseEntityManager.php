<?php

namespace App\Customization\Application;

use App\Customization\Domain\TermsOfUse;

interface TermsOfUseEntityManager
{
    public function create(TermsOfUse $policy): void;
    public function flush(): void;
    public function deleteAll(): void;
}