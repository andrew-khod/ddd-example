<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\UseCase\ArchiveInitiative;

use App\Identity\Application\User\AuthenticatedCustomer;
use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use App\Initiative\Application\Initiative\InitiativeEntityManager;
use App\Initiative\Application\Initiative\InitiativeException;
use App\Initiative\Application\Initiative\Query\InitiativeByCriteriaQuery;
use App\Initiative\Application\Initiative\Query\InitiativeByIdCriteria;
use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Initiative\InitiativeId;
use App\Initiative\Domain\Initiative\InitiativeWorkflow;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\ActiveLanguage;

final class ArchiveInitiativeHandler
{
    private InitiativeByCriteriaQuery $initiativeByCriteriaQuery;
    private AuthenticatedCustomer $authenticatedCustomer;
    private InitiativeEntityManager $initiativeEntityManager;
//    private WorkflowInterface $initiativeWorkflow;
    private InitiativeWorkflow $initiativeWorkflow;
    private MessageBus $messageBus;
    private SwitchableActiveTenant $tenant;
    private ActiveLanguage $activeLanguage;

    public function __construct(
                                InitiativeWorkflow $initiativeWorkflow,
//                                WorkflowInterface $initiativeWorkflow,
                                InitiativeByCriteriaQuery $initiativeByCriteriaQuery,
                                InitiativeEntityManager $initiativeEntityManager,
                                MessageBus $messageBus,
                                ActiveLanguage $activeLanguage,
                                SwitchableActiveTenant $tenant,
                                AuthenticatedCustomer $authenticatedCustomer)
    {
        $this->initiativeByCriteriaQuery = $initiativeByCriteriaQuery;
        $this->authenticatedCustomer = $authenticatedCustomer;
        $this->initiativeEntityManager = $initiativeEntityManager;
        $this->initiativeWorkflow = $initiativeWorkflow;
        $this->messageBus = $messageBus;
        $this->tenant = $tenant;
        $this->activeLanguage = $activeLanguage;
    }

    public function handle(ArchiveInitiativeCommand $command): Initiative
    {
        $criteria = new InitiativeByIdCriteria(new InitiativeId($command->initiative()));
        $initiative = $this->initiativeByCriteriaQuery->queryOne($criteria);

        if (!$initiative) {
            throw InitiativeException::initiativeNotExist();
        }

        if (!$initiative->isOwnedBy($this->authenticatedCustomer->user())) {
            throw InitiativeException::notInitiativeOwner();
        }

//        $status = new Archived();
//        $initiative->setStatus($status, $this->initiativeWorkflow);
//        $initiative->setStatusTESTING($status, $this->initiativeWorkflow);

//        $this->initiativeWorkflow->apply($initiative, 'update');
//        $this->initiativeWorkflow->apply($initiative, 'archive');
//        $this->initiativeWorkflow->apply($initiative, 'expireArchived');
//        $this->initiativeWorkflow->apply($initiative, 'delete');
        // TODO OR
        $initiative->archive();

        $this->initiativeEntityManager->update();

        // fixme don't pass ActiveLanguage, use activeLanguage of each follower instead
        $this->messageBus->dispatch(new InitiativeArchived(
            $this->tenant->company(),
            $initiative->id(),
            $initiative->briefing()->title(),
            $this->activeLanguage)
        );

        return $initiative;

//        $this->initiativeWorkflow->apply($initiative, 'archive');

//        $a = $this->initiativeWorkflow->getMarking($initiative);
//        $initiativeWorkflow->apply($initiative, 'republish');

//        $this->initiativeWorkflow->apply($initiative, 'archive');
    }
}
