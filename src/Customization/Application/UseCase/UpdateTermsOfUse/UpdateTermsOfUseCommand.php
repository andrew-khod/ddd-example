<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\UpdateTermsOfUse;

final class UpdateTermsOfUseCommand
{
    private array $terms;

    public function __construct(array $terms)
    {
        $this->terms = $terms;
    }

    public function terms(): array
    {
        return $this->terms;
    }
}
