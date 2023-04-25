<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\UseCase\FollowInitiative;

//use App\Identity\Application\Customer\CustomerEntityManager;
use App\Identity\Application\User\AuthenticatedCustomer;
use App\Initiative\Application\Initiative\InitiativeEntityManager;
use App\Initiative\Application\Initiative\InitiativeException;
use App\Initiative\Application\Initiative\Query\InitiativeByCriteriaQuery;
use App\Initiative\Application\Initiative\Query\InitiativeByIdCriteria;
use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Initiative\InitiativeId;
use App\Shared\Application\EntityAlreadyExistException;

final class FollowInitiativeHandler
{
    private InitiativeByCriteriaQuery $initiativeByCriteriaQuery;
    private AuthenticatedCustomer $authenticatedCustomer;
//    private CustomerEntityManager $customerEntityManager;
    private InitiativeEntityManager $initiativeEntityManager;

    public function __construct(InitiativeByCriteriaQuery $initiativeByCriteriaQuery,
//                                CustomerEntityManager $customerEntityManager,
                                InitiativeEntityManager $initiativeEntityManager,
                                AuthenticatedCustomer $authenticatedCustomer)
    {
        $this->initiativeByCriteriaQuery = $initiativeByCriteriaQuery;
        $this->authenticatedCustomer = $authenticatedCustomer;
//        $this->customerEntityManager = $customerEntityManager;
        $this->initiativeEntityManager = $initiativeEntityManager;
    }

    public function handle(FollowInitiativeCommand $command): Initiative
    {
        $criteria = new InitiativeByIdCriteria(new InitiativeId($command->initiative()));
        $initiative = $this->initiativeByCriteriaQuery->queryOne($criteria);
        $customer = $this->authenticatedCustomer->user();

        if (!$initiative) {
            throw InitiativeException::initiativeNotExist();
        }

        $initiative->addFollower($customer);

        try {
            $this->initiativeEntityManager->update();
//            $this->customerEntityManager->update();
        } catch (EntityAlreadyExistException $exception) {
            throw InitiativeException::customerAlreadyJoined();
        }

        return $initiative;
    }
}
