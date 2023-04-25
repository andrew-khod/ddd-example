<?php

declare(strict_types=1);

namespace App\Initiative\Application\Comment\UseCase\Dashboard\RemoveComment;

use App\Initiative\Application\Comment\CommentEntityManager;
use App\Initiative\Application\Comment\Query\CommentByCriteriaQuery;
use App\Initiative\Application\Comment\Query\CommentByIdCriteria;
use App\Initiative\Domain\Comment\CommentId;

final class RemoveCommentHandler
{
//    private InitiativeByCriteriaBaseQuery $initiativeByCriteriaQuery;
//    private AuthenticatedUser $authenticatedUser;
//    private InitiativeEntityManager $initiativeEntityManager;
////    private WorkflowInterface $initiativeWorkflow;
//    private InitiativeWorkflow $initiativeWorkflow;
    private CommentByCriteriaQuery $commentByCriteriaQuery;
    private CommentEntityManager $commentEntityManager;
//    private InitiativeEntityManager $initiativeEntityManager;

    public function __construct(CommentByCriteriaQuery $commentByCriteriaQuery, CommentEntityManager $commentEntityManager)
//    public function __construct(CommentByCriteriaQuery $commentByCriteriaQuery, InitiativeEntityManager $initiativeEntityManager)
//                                WorkflowInterface $initiativeWorkflow,
//                                InitiativeByCriteriaBaseQuery $initiativeByCriteriaQuery,
//                                InitiativeEntityManager $initiativeEntityManager,
//                                AuthenticatedUser $authenticatedUser)
    {
//        $this->initiativeByCriteriaQuery = $initiativeByCriteriaQuery;
//        $this->authenticatedUser = $authenticatedUser;
//        $this->initiativeEntityManager = $initiativeEntityManager;
//        $this->initiativeWorkflow = $initiativeWorkflow;
        $this->commentByCriteriaQuery = $commentByCriteriaQuery;
//        $this->commentEntityManager = $commentEntityManager;
//        $this->initiativeEntityManager = $initiativeEntityManager;
        $this->commentEntityManager = $commentEntityManager;
    }

//    public function handle(ReportCommentCommand $command): Initiative
    public function handle(RemoveCommentCommand $command): void
    {
        $criteria = new CommentByIdCriteria(new CommentId($command->comment()));
        $comment = $this->commentByCriteriaQuery->queryOne($criteria);

        $comment->archive();
//        $comment->initiative()->removeComment($comment);

//        $this->initiativeEntityManager->update();
        $this->commentEntityManager->update();
    }
}
