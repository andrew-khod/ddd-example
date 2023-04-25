<?php

namespace App\Identity\Infrastructure\Customer\SignUp;

use App\Identity\Application\Customer\UseCase\SignUpCustomer\NotActivatedCustomerCreated;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CustomerActivationHandler implements MessageHandlerInterface
{
    private CustomerActivationEmailSender $customerPreRegisteredEmailSender;

    public function __construct(CustomerActivationEmailSender $customerPreRegisteredEmailSender)
    {
        $this->customerPreRegisteredEmailSender = $customerPreRegisteredEmailSender;
    }

    public function __invoke(NotActivatedCustomerCreated $message)
    {
        $this->customerPreRegisteredEmailSender->send($message);
    }
}
