<?php

declare(strict_types=1);

namespace App\Identity\Application\User\UseCase\SwitchCompany;

final class SwitchCompanyCommand
{
    private string $company;

    public function __construct(string $company)
    {
        $this->company = $company;
    }

    public function company(): string
    {
        return $this->company;
    }
}
