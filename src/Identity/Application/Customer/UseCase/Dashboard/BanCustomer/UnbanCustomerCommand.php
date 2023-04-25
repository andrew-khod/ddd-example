<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\Dashboard\BanCustomer;

final class UnbanCustomerCommand
{
    private string $customer;

    public function __construct(string $customer)
    {
        $this->customer = $customer;
    }

    public function customer(): string
    {
        return $this->customer;
    }
}
