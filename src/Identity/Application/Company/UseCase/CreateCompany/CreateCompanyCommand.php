<?php

namespace App\Identity\Application\Company\UseCase\CreateCompany;

final class CreateCompanyCommand
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }
}
