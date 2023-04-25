<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\UpdatePrivacyPolicy;

final class UpdatePrivacyPolicyCommand
{
    private array $policy;

    public function __construct(array $policy)
    {
        $this->policy = $policy;
    }

    public function policy(): array
    {
        return $this->policy;
    }
}
