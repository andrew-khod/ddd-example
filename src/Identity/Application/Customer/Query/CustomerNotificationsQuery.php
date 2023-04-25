<?php

namespace App\Identity\Application\Customer\Query;

use App\Identity\Domain\Customer\Customer;

interface CustomerNotificationsQuery
{
    public function queryAsPayload(Customer $customer): array;
    public function query(Customer $customer): array;
}
