<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\UseCase\CommentInitiative;

use App\Identity\Application\User\AuthenticatedCustomer;
use App\Initiative\Application\Initiative\InitiativeEntityManager;
use App\Initiative\Application\Initiative\InitiativeException;
use App\Initiative\Application\Initiative\Query\InitiativeByCriteriaQuery;
use App\Initiative\Application\Initiative\Query\InitiativeByIdCriteria;
use App\Initiative\Domain\Comment\Comment;
use App\Initiative\Domain\Comment\CommentId;
use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Initiative\InitiativeId;
use App\Shared\Application\ActiveTenant;
use App\Shared\Application\MessageBus;

final class CommentInitiativeHandler
{
    private InitiativeByCriteriaQuery $initiativeByCriteriaQuery;
    private InitiativeEntityManager $initiativeEntityManager;
    private AuthenticatedCustomer $authenticatedCustomer;
    private MessageBus $messageBus;
    private ActiveTenant $tenant;

    public function __construct(InitiativeByCriteriaQuery $initiativeByCriteriaQuery,
                                AuthenticatedCustomer $authenticatedCustomer,
                                MessageBus $messageBus,
                                ActiveTenant $tenant,
                                InitiativeEntityManager $initiativeEntityManager)
    {
        $this->initiativeByCriteriaQuery = $initiativeByCriteriaQuery;
        $this->initiativeEntityManager = $initiativeEntityManager;
        $this->authenticatedCustomer = $authenticatedCustomer;
        $this->messageBus = $messageBus;
        $this->tenant = $tenant;
    }

    public function handle(CommentInitiativeCommand $command): Initiative
    {
        $criteria = new InitiativeByIdCriteria(new InitiativeId($command->initiative()));
        $initiative = $this->initiativeByCriteriaQuery->queryOne($criteria);
        $id = new CommentId((string) $this->initiativeEntityManager->nextId());

        // TODO create Query method throwing the exception
        if (!$initiative) {
            throw InitiativeException::initiativeNotExist();
        }

        $comment = new Comment(
            $id,
            $command->comment(),
            $initiative,
            $this->authenticatedCustomer->user()
        );

        $initiative->comment($comment);

        $this->initiativeEntityManager->update();

        // fixme dont put whole Comment in bus, put scalars instead
        $this->messageBus->dispatch(new InitiativeCommented(
            $this->tenant->company(),
            $initiative->id(),
            $comment->id()
//            $comment
        ));

        return $initiative;
    }
}
