<?php

namespace App\Identity\Application\Permission\Query;

use App\Identity\Domain\Permission\PermissionConfiguration;

interface PermissionConfigurationQuery
{
    public function query(): PermissionConfiguration;
}
