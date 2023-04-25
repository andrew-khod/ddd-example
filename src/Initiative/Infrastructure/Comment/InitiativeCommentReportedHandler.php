<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Comment;

use App\Initiative\Application\Comment\UseCase\Web\ReportComment\InitiativeCommentReported;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class InitiativeCommentReportedHandler implements MessageHandlerInterface
{
    private InitiativeCommentReportedSender $initiativeCommentReportedSender;

    public function __construct(InitiativeCommentReportedSender $initiativeCommentReportedSender)
    {
        $this->initiativeCommentReportedSender = $initiativeCommentReportedSender;
    }

    public function __invoke(InitiativeCommentReported $message)
    {
        $this->initiativeCommentReportedSender->send($message);
    }
}