<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Comment;

use App\Initiative\Application\Initiative\UseCase\CommentInitiative\InitiativeCommented;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class InitiativeCommentedHandler implements MessageHandlerInterface
{
    private InitiativeCommentedSender $initiativeCommentedSender;

    public function __construct(InitiativeCommentedSender $initiativeCommentedSender)
    {
        $this->initiativeCommentedSender = $initiativeCommentedSender;
    }

    public function __invoke(InitiativeCommented $message)
    {
        $this->initiativeCommentedSender->send($message);
    }
}