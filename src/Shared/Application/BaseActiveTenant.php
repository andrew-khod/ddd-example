<?php

namespace App\Shared\Application;

use App\Identity\Domain\Company\Company;

interface BaseActiveTenant
{
    public function company(): ?Company;
}