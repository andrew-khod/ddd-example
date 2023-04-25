<?php

declare(strict_types=1);

namespace App\Initiative\Application\Comment\UseCase\Web\ReportComment;

use App\Identity\Application\User\AuthenticatedCustomer;
use App\Identity\Infrastructure\Company\ActiveTenant;
use App\Initiative\Application\Comment\Query\CommentByCriteriaQuery;
use App\Initiative\Application\Comment\Query\CommentByIdCriteria;
use App\Initiative\Application\Initiative\InitiativeException;
use App\Initiative\Domain\Comment\CommentId;
use App\Shared\Application\MessageBus;

final class ReportCommentHandler
{
    private MessageBus $messageBus;
    private CommentByCriteriaQuery $commentByCriteriaQuery;
    private AuthenticatedCustomer $authenticatedCustomer;
    private ActiveTenant $activeTenant;

    public function __construct(MessageBus             $messageBus,
                                CommentByCriteriaQuery $commentByCriteriaQuery,
                                AuthenticatedCustomer  $authenticatedCustomer,
                                ActiveTenant $activeTenant)
    {
        $this->messageBus = $messageBus;
        $this->commentByCriteriaQuery = $commentByCriteriaQuery;
        $this->authenticatedCustomer = $authenticatedCustomer;
        $this->activeTenant = $activeTenant;
    }

    public function handle(ReportCommentCommand $command): void
    {
        $criteria = new CommentByIdCriteria(new CommentId($command->comment()));
        $comment = $this->commentByCriteriaQuery->queryOne($criteria);

        if (!$comment) {
            throw InitiativeException::commentNotExist();
        }

        $this->messageBus->dispatch(new InitiativeCommentReported($comment,
            $command->reason(),
            $command->message(),
            $this->authenticatedCustomer->user(),
            $this->activeTenant->company(),
            $command->url()));
    }
}
