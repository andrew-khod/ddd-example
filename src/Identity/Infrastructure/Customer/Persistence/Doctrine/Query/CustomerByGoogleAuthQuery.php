<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Customer\Persistence\Doctrine\Query;

use App\Identity\Application\Customer\Query\CustomerByCriteriaQuery;
use App\Identity\Application\Customer\Query\CustomerByEmailCriteria;
use App\Identity\Application\Customer\Query\CustomerByGoogleAuthQuery as CustomerByGoogleAuthQueryInterface;
use App\Identity\Application\User\Exception\UserException;
use App\Identity\Application\User\UseCase\LinkGoogleAuth\GoogleAuth;
use App\Identity\Application\User\UseCase\LinkGoogleAuth\GoogleUser;
use App\Identity\Domain\Customer\Customer;
use App\Identity\Domain\User\Email;

final class CustomerByGoogleAuthQuery implements CustomerByGoogleAuthQueryInterface
{
    private GoogleAuth $googleAuth;

    private CustomerByCriteriaQuery $customerByCriteriaQuery;
    private BannedCustomerByCriteriaQuery $bannedCustomerByCriteriaQuery;

    public function __construct(GoogleAuth $googleAuth,
                                BannedCustomerByCriteriaQuery $bannedCustomerByCriteriaQuery,
                                CustomerByCriteriaQuery $customerByCriteriaQuery)
    {
        $this->googleAuth = $googleAuth;
        $this->customerByCriteriaQuery = $customerByCriteriaQuery;
        $this->bannedCustomerByCriteriaQuery = $bannedCustomerByCriteriaQuery;
    }

    public function queryByOneTimeCode(string $oneTimeCode): ?Customer
    {
        $oauthUser = $this->googleAuth->userByOneTimeCode($oneTimeCode);
        $email = new Email($oauthUser->email());

        $criteria = new CustomerByEmailCriteria($email);

        return $this->customerByCriteriaQuery->queryOne($criteria);
    }

    public function queryByGoogleUser(GoogleUser $user): ?Customer
    {
        $email = new Email($user->email());

        $criteria = new CustomerByEmailCriteria($email);

        // todo use single query for Customer/BannedCustomer entities, to do so look at AllCustomerQuery example
        $customer = $this->customerByCriteriaQuery->queryOne($criteria);

        if (!$customer && $this->bannedCustomerByCriteriaQuery->queryOne($criteria)) {
            throw UserException::banned();
        }

        return $this->customerByCriteriaQuery->queryOne($criteria);
    }
}
