<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\UseCase\Dashboard\RestoreInitiative;

use App\Identity\Application\User\AuthenticatedUser;
use App\Initiative\Application\Initiative\InitiativeEntityManager;
use App\Initiative\Application\Initiative\InitiativeException;
use App\Initiative\Application\Initiative\Query\InitiativeByCriteriaBaseQuery;
use App\Initiative\Application\Initiative\Query\InitiativeByIdCriteria;
use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Initiative\InitiativeId;
use App\Initiative\Domain\Initiative\InitiativeWorkflow;

// todo instead of duplicating similar Handlers for Dashboard and Web, think about dynamically passing necessary
// todo TenantAwareQuery or BaseQuery or SwitchableTenantQuery by IOC container
final class RestoreInitiativeHandler
{
    private InitiativeByCriteriaBaseQuery $initiativeByCriteriaQuery;
    private AuthenticatedUser $authenticatedUser;
    private InitiativeEntityManager $initiativeEntityManager;
//    private WorkflowInterface $initiativeWorkflow;
    private InitiativeWorkflow $initiativeWorkflow;

    public function __construct(InitiativeWorkflow $initiativeWorkflow,
//                                WorkflowInterface $initiativeWorkflow,
                                InitiativeByCriteriaBaseQuery $initiativeByCriteriaQuery,
                                InitiativeEntityManager $initiativeEntityManager,
                                AuthenticatedUser $authenticatedUser)
    {
        $this->initiativeByCriteriaQuery = $initiativeByCriteriaQuery;
        $this->authenticatedUser = $authenticatedUser;
        $this->initiativeEntityManager = $initiativeEntityManager;
        $this->initiativeWorkflow = $initiativeWorkflow;
    }

    public function handle(RestoreInitiativeCommand $command): Initiative
    {
        $criteria = new InitiativeByIdCriteria(new InitiativeId($command->initiative()));
        $initiative = $this->initiativeByCriteriaQuery->queryOne($criteria);

        if (!$initiative) {
            throw InitiativeException::initiativeNotExist();
        }

        // todo check if superadmin or attached to company

//        $status = new Archived();
//        $initiative->setStatus($status, $this->initiativeWorkflow);
//        $initiative->setStatusTESTING($status, $this->initiativeWorkflow);

//        $this->initiativeWorkflow->apply($initiative, 'update');
//        $this->initiativeWorkflow->apply($initiative, 'archive');
//        $this->initiativeWorkflow->apply($initiative, 'expireArchived');
//        $this->initiativeWorkflow->apply($initiative, 'delete');
        // TODO OR
        $initiative->restore();

        $this->initiativeEntityManager->update();

        return $initiative;

//        $this->initiativeWorkflow->apply($initiative, 'archive');

//        $a = $this->initiativeWorkflow->getMarking($initiative);
//        $initiativeWorkflow->apply($initiative, 'republish');

//        $this->initiativeWorkflow->apply($initiative, 'archive');
    }
}
