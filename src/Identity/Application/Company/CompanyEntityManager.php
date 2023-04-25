<?php

namespace App\Identity\Application\Company;

use App\Identity\Domain\Company\Company;
use App\Identity\Domain\Company\CompanyId;

interface CompanyEntityManager
{
    public function save(Company $company): void;

    public function update(): void;

    public function nextId(): CompanyId;
}
