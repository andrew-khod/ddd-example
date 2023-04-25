<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\UseCase\RemoveFavouriteInitiative;

use App\Identity\Application\Customer\CustomerEntityManager;
use App\Identity\Application\User\AuthenticatedCustomer;
use App\Initiative\Application\Initiative\InitiativeException;
use App\Initiative\Application\Initiative\Query\InitiativeByCriteriaQuery;
use App\Initiative\Application\Initiative\Query\InitiativeByIdCriteria;
use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Initiative\InitiativeId;

final class RemoveFavouriteInitiativeHandler
{
    public function __construct(InitiativeByCriteriaQuery $initiativeByCriteriaQuery,
                                CustomerEntityManager $customerEntityManager,
                                AuthenticatedCustomer $authenticatedCustomer)
    {
        $this->initiativeByCriteriaQuery = $initiativeByCriteriaQuery;
        $this->authenticatedCustomer = $authenticatedCustomer;
        $this->customerEntityManager = $customerEntityManager;
    }

    public function handle(RemoveFavouriteInitiativeCommand $command): Initiative
    {
        $criteria = new InitiativeByIdCriteria(new InitiativeId($command->initiative()));
        $initiative = $this->initiativeByCriteriaQuery->queryOne($criteria);
        $customer = $this->authenticatedCustomer->user();

        if (!$initiative) {
            throw InitiativeException::initiativeNotExist();
        }

        $customer->removeFavourite($initiative);

        $this->customerEntityManager->update();

        return $initiative;
    }
}
