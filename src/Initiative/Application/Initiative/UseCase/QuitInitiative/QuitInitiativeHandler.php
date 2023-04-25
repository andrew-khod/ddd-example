<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\UseCase\QuitInitiative;

use App\Identity\Application\User\AuthenticatedCustomer;
use App\Initiative\Application\Initiative\InitiativeEntityManager;
use App\Initiative\Application\Initiative\InitiativeException;
use App\Initiative\Application\Initiative\Query\InitiativeByCriteriaQuery;
use App\Initiative\Application\Initiative\Query\InitiativeByIdCriteria;
use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Initiative\InitiativeId;

final class QuitInitiativeHandler
{
    private InitiativeByCriteriaQuery $initiativeByCriteriaQuery;
    private AuthenticatedCustomer $authenticatedCustomer;
    private InitiativeEntityManager $initiativeEntityManager;

    public function __construct(InitiativeByCriteriaQuery $initiativeByCriteriaQuery,
                                InitiativeEntityManager $initiativeEntityManager,
                                AuthenticatedCustomer $authenticatedCustomer)
    {
        $this->initiativeByCriteriaQuery = $initiativeByCriteriaQuery;
        $this->authenticatedCustomer = $authenticatedCustomer;
        $this->initiativeEntityManager = $initiativeEntityManager;
    }

    public function handle(QuitInitiativeCommand $command): Initiative
    {
        $criteria = new InitiativeByIdCriteria(new InitiativeId($command->initiative()));
        $initiative = $this->initiativeByCriteriaQuery->queryOne($criteria);
        $customer = $this->authenticatedCustomer->user();

        if (!$initiative) {
            throw InitiativeException::initiativeNotExist();
        }

        $initiative->quit($customer);
        $this->initiativeEntityManager->update();

        return $initiative;
    }
}
