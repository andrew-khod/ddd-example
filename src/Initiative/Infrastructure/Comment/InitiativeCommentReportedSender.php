<?php

namespace App\Initiative\Infrastructure\Comment;

use App\Identity\Application\User\Query\AllUserCriteria;
use App\Identity\Domain\User\User;
use App\Identity\Infrastructure\User\Persistence\Doctrine\Query\UserByCriteriaQuery;
use App\Initiative\Application\Comment\UseCase\Web\ReportComment\InitiativeCommentReported;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

class InitiativeCommentReportedSender
{
    private MailerInterface $mailer;
//    private TranslatorInterface $translator;
    private UserByCriteriaQuery $userByCriteriaQuery;

    public function __construct(MailerInterface     $mailer,
                                TranslatorInterface $translator,
                                UserByCriteriaQuery $userByCriteriaQuery)
    {
        $this->mailer = $mailer;
//        $this->translator = $translator;
        $this->userByCriteriaQuery = $userByCriteriaQuery;
    }

    public function send(InitiativeCommentReported $message)
    {
//        $admins = [new Address($user->email()->value())];
        $criteria = new AllUserCriteria();
//        $this->userByCriteriaQuery->tenant($message->company());
        // todo select only company related
        $admins = $this->userByCriteriaQuery->queryMultiple($criteria);

        $admins = array_map(
            fn(User $user) => new Address((string) $user->email()),
            $admins->toArray()
        );
        $email = (new TemplatedEmail())
            ->to(...$admins)
            ->subject('Comment Reported')
            ->htmlTemplate('/Initiative/Infrastructure/Comment/comment_reported.html.twig')
            ->context([
                'reporter' => $message->reporter(),
                'comment' => $message->comment(),
                'reason' => $message->reason(),
                'message' => $message->message(),
                'url' => $message->url(),
            ]);

        $this->mailer->send($email);
    }
}