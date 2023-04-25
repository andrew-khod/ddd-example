<?php

namespace App\Identity\Application\AuthVendor\Query;

use App\Identity\Domain\AuthVendor\AuthVendor;
use App\Identity\Domain\AuthVendor\AuthVendorCollection;

interface AuthVendorByCriteriaQuery
{
    public function queryOne(AuthVendorCriteria $criteria): ?AuthVendor;

    public function queryMultiple(AuthVendorCriteria $criteria): AuthVendorCollection;
}
