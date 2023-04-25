<?php

namespace App\Identity\Application\Permission\Query;

use App\Identity\Domain\Permission\PermissionCollection;

interface PermissionByCriteriaQuery
{
    public function queryMultiple(PermissionCriteria $criteria): PermissionCollection;
}
