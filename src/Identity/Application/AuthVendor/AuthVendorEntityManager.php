<?php

namespace App\Identity\Application\AuthVendor;

use App\Identity\Domain\AuthVendor\AuthVendor;

interface AuthVendorEntityManager
{
    public function save(AuthVendor $authVendor): void;
}
