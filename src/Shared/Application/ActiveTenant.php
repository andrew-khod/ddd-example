<?php

namespace App\Shared\Application;

// TODO IMPORTANT! THERE ARE CROSS BOUNDARY VIOLATIONS FOUND IN EACH OF BOUNDED CONTEXT, WHEN ONE BC IMPORTS ANOTHERS MODULES
// TODO FIX THIS BY CHOOSING ANY METHOD LIKE MERGE BC, MOVE TO SHARED OR ANOTHER METHODS
use App\Identity\Domain\Company\Company;

interface ActiveTenant
{
    public function company(): ?Company;
}