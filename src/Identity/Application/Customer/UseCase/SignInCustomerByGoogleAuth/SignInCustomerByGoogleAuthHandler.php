<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\SignInCustomerByGoogleAuth;

use App\Identity\Application\Customer\CustomerEntityManager;
use App\Identity\Application\Customer\Query\CustomerByGoogleAuthQuery;
use App\Identity\Application\Customer\UseCase\CreateCustomer\CreateCustomerCommand;
use App\Identity\Application\Customer\UseCase\CreateCustomer\CreateCustomerHandler;
use App\Identity\Application\Security\Security;
use App\Identity\Application\User\UseCase\LinkGoogleAuth\GoogleAuth;
use App\Identity\Domain\Customer\Customer;

final class SignInCustomerByGoogleAuthHandler
{
    private CustomerByGoogleAuthQuery $customerByGoogleAuthQuery;

    private GoogleAuth $googleAuth;

    private CreateCustomerHandler $createCustomerHandler;

    private CustomerEntityManager $customerEntityManager;

    private Security $security;

    public function __construct(CustomerByGoogleAuthQuery $customerByGoogleAuthQuery,
                                CreateCustomerHandler $createCustomerHandler,
                                CustomerEntityManager $customerEntityManager,
                                Security $security,
                                GoogleAuth $googleAuth)
    {
        $this->customerByGoogleAuthQuery = $customerByGoogleAuthQuery;
        $this->googleAuth = $googleAuth;
        $this->createCustomerHandler = $createCustomerHandler;
        $this->customerEntityManager = $customerEntityManager;
        $this->security = $security;
    }

    public function handle(SignInCustomerByGoogleAuthCommand $command): Customer
    {
        $code = $command->code();

        $oauthUser = $this->googleAuth->userByOneTimeCode($code);
        $customer = $this->customerByGoogleAuthQuery->queryByGoogleUser($oauthUser);
        $username = $this->customerEntityManager->nextId()->value();
        $password = $this->security->randomPassword();

        if (!$customer) {
            $command = new CreateCustomerCommand(
                $oauthUser->email(),
                sprintf('%s %s', $oauthUser->firstname(), $oauthUser->lastname()),
//                $username,
                $password->hash(),
                $oauthUser->firstname(),
                $oauthUser->lastname(),
            );
            $customer = $this->createCustomerHandler->handle($command);
        }

        // TODO $customer->allowAuthVendor();

        return $customer;
    }
}
