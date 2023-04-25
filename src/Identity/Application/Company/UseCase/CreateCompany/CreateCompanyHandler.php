<?php

declare(strict_types=1);

namespace App\Identity\Application\Company\UseCase\CreateCompany;

use App\Identity\Application\Company\CompanyEntityManager;
use App\Identity\Domain\Company\Company;
use App\Identity\Domain\Company\Name;

final class CreateCompanyHandler
{
    private CompanyEntityManager $companyEntityManager;

    public function __construct(CompanyEntityManager $companyEntityManager)
    {
        $this->companyEntityManager = $companyEntityManager;
    }

    public function handle(CreateCompanyCommand $command): Company
    {
        $name = $command->name();
        $name = new Name($name);
        $id = $this->companyEntityManager->nextId();
        $company = new Company($id, $name);

        $this->companyEntityManager->save($company);

        return $company;
    }
}
