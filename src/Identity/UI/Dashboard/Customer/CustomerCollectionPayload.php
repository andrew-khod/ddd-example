<?php

namespace App\Identity\UI\Dashboard\Customer;

use App\Identity\Domain\Customer\CustomerCollection;
use App\Shared\UI\DashboardPayload;

class CustomerCollectionPayload implements DashboardPayload
{
    private CustomerCollection $customers;
//    private array $notActivatedCustomers;

//    public function __construct(CustomerCollection $customers, array $notActivatedCustomers)
    public function __construct(CustomerCollection $customers)
    {
        $this->customers = $customers;
//        $this->notActivatedCustomers = $notActivatedCustomers;
    }

//    public function get(): array
    public function get(): CustomerCollection
    {
        return $this->customers;
//        return [
//            ...$this->customers->toArray(),
//            ...$this->notActivatedCustomers,
//        ];
    }
}