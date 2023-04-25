<?php

declare(strict_types=1);

namespace App\Identity\Domain\Customer;

use App\Shared\Domain\BaseCollection;

final class CustomerCollection extends BaseCollection
{
//    public function __construct(Customer ...$customers)
    public function __construct(BaseCustomer ...$customers)
    {
        parent::__construct(...$customers);
    }

    public function active(): self
    {
        return new self(...array_filter(
            $this->items,
            fn(BaseCustomer $customer) => $customer instanceof Customer)
        );
    }
}