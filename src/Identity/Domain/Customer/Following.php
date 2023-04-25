<?php

declare(strict_types=1);

namespace App\Identity\Domain\Customer;

use App\Initiative\Domain\Initiative\Initiative;
use DateTime;
use Symfony\Component\Uid\Uuid;

class Following
{
    private Uuid $id;
    private ?AbstractCustomer $customer;
    private ?Initiative $initiative;
    private DateTime $created;

    public function __construct(Initiative $initiative, Customer $customer)
    {
        $this->initiative = $initiative;
        $this->customer = $customer;
    }

    public function initiative(): ?Initiative
    {
        return $this->initiative;
    }

    public function customer(): ?AbstractCustomer
    {
        return $this->customer;
    }

    public function delete(): void
    {
        $this->customer = null;
        $this->initiative = null;
    }
}