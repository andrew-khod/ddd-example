<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\Dashboard\BanCustomer;

use App\Identity\Application\Customer\CustomerEntityManager;
use App\Identity\Application\Customer\Query\CustomerByCriteriaQuery;
use App\Identity\Domain\Customer\BannedCustomer;
use App\Identity\Domain\Customer\CustomerId;

final class BanCustomerHandler
{
    private CustomerEntityManager $customerEntityManager;
//    private CustomerByCriteriaQuery $query;

    public function __construct(CustomerEntityManager $customerEntityManager, CustomerByCriteriaQuery $query)
    {
        $this->customerEntityManager = $customerEntityManager;
//        $this->query = $query;
    }

    public function handle(BanCustomerCommand $command): BannedCustomer
    {
        // todo entity not found exception without this call, so finally UoW & IdentityMap got configured wrong
//        $c=$this->query->queryOne(new CustomerByIdCriteria(new CustomerId($command->customer())));

        $customer = new BannedCustomer(new CustomerId($command->customer()));

//        $this->customerEntityManager->sync($customer);
        $this->customerEntityManager->update();
        $this->customerEntityManager->updateInheritanceType($customer, BannedCustomer::TYPE);

        return $customer;
    }
}
