<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\Dashboard\BanCustomer;

use App\Identity\Application\Customer\CustomerEntityManager;
use App\Identity\Application\Customer\Query\BannedCustomerByCriteriaQuery;
use App\Identity\Application\Customer\Query\CustomerByIdCriteria;
use App\Identity\Domain\Customer\Customer;
use App\Identity\Domain\Customer\CustomerId;

final class UnbanCustomerHandler
{
    private CustomerEntityManager $customerEntityManager;
    private BannedCustomerByCriteriaQuery $query;

    public function __construct(CustomerEntityManager $customerEntityManager, BannedCustomerByCriteriaQuery $bannedCustomerByCriteriaQuery)
    {
        $this->customerEntityManager = $customerEntityManager;
        $this->query = $bannedCustomerByCriteriaQuery;
    }

//    public function handle(UnbanCustomerCommand $command): Customer
    public function handle(UnbanCustomerCommand $command): void
    {
        // todo implement Workflow for Customer statuses
        // todo entity not found exception without this call, so finally UoW & IdentityMap got configured wrong
        $customer = $this->query->queryOne(new CustomerByIdCriteria(new CustomerId($command->customer())));
//        $c=$this->query->queryOne(new CustomerByIdCriteria(new CustomerId($command->customer())));

//        $customer = new Customer(new CustomerId($command->customer()));

//        $this->customerEntityManager->sync($customer);
//        $this->customerEntityManager->update();
        $this->customerEntityManager->updateInheritanceType($customer, Customer::TYPE);

//        return $customer;
    }
}
