<?php

namespace App\Identity\Application\Customer\UseCase\SignUpCustomer;

use App\Identity\Domain\Customer\NotActivatedCustomer;
use App\Shared\Application\AsyncMessage;
use App\Shared\Domain\ActiveLanguage;

class NotActivatedCustomerCreated implements AsyncMessage
{
    private NotActivatedCustomer $customer;
    private ActiveLanguage $activeLanguage;
    private string $url;

    public function __construct(NotActivatedCustomer $customer, ActiveLanguage $activeLanguage, string $url)
    {
        $this->customer = $customer;
        $this->activeLanguage = $activeLanguage;
        $this->url = $url;
    }

    public function customer(): NotActivatedCustomer
    {
        return $this->customer;
    }

    public function language(): ActiveLanguage
    {
        return $this->activeLanguage;
    }

    public function url(): string
    {
        return $this->url;
    }
}
