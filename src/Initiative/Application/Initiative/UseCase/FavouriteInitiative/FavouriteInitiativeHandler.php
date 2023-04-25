<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\UseCase\FavouriteInitiative;

use App\Identity\Application\User\AuthenticatedCustomer;
use App\Initiative\Application\Initiative\InitiativeEntityManager;
use App\Initiative\Application\Initiative\InitiativeException;
use App\Initiative\Application\Initiative\Query\InitiativeByCriteriaQuery;
use App\Initiative\Application\Initiative\Query\InitiativeByIdCriteria;
use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Initiative\InitiativeId;
use App\Shared\Application\MessageBus;

final class FavouriteInitiativeHandler
{
    private MessageBus $messageBus;
    private InitiativeByCriteriaQuery $initiativeByCriteriaQuery;
    private AuthenticatedCustomer $authenticatedCustomer;
    private InitiativeEntityManager $initiativeEntityManager;

    public function __construct(InitiativeByCriteriaQuery $initiativeByCriteriaQuery,
                                InitiativeEntityManager   $initiativeEntityManager,
                                MessageBus                $messageBus,
                                AuthenticatedCustomer     $authenticatedCustomer)
    {
        $this->initiativeByCriteriaQuery = $initiativeByCriteriaQuery;
        $this->authenticatedCustomer = $authenticatedCustomer;
        $this->initiativeEntityManager = $initiativeEntityManager;
        $this->messageBus = $messageBus;
    }

    public function handle(FavouriteInitiativeCommand $command): Initiative
    {
        $criteria = new InitiativeByIdCriteria(new InitiativeId($command->initiative()));
        $initiative = $this->initiativeByCriteriaQuery->queryOne($criteria);
        $customer = $this->authenticatedCustomer->user();

        if (!$initiative) {
            throw InitiativeException::initiativeNotExist();
        }

        $customer->favourite($initiative);

        $this->initiativeEntityManager->update();

        $this->messageBus->dispatch(new InitiativeFavourited($initiative->id(), $customer->id()));

        return $initiative;
    }
}
