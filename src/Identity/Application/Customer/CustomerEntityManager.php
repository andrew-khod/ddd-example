<?php

namespace App\Identity\Application\Customer;

use App\Identity\Domain\Customer\BaseCustomer;
use App\Identity\Domain\Customer\CustomerId;

interface CustomerEntityManager
{
    public function create(BaseCustomer $customer): void;

    public function update(): void;

    public function sync(object $entity): object;

    public function nextId(): CustomerId;

    public function updateInheritanceType($entity, string $type);
}
