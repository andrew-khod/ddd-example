<?php

namespace App\Customization\Application;

use App\Customization\Domain\PrivacyPolicy;

interface PrivacyPolicyEntityManager
{
    public function create(PrivacyPolicy $policy): void;
    public function flush(): void;
    public function deleteAll(): void;
}