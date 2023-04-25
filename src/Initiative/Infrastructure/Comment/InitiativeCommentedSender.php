<?php

namespace App\Initiative\Infrastructure\Comment;

use App\Identity\Domain\Customer\Customer;
use App\Identity\Infrastructure\Customer\ActiveLanguage;
use App\Initiative\Application\Comment\Query\CommentByIdCriteria;
use App\Initiative\Application\Initiative\UseCase\CommentInitiative\InitiativeCommented;
use App\Initiative\Infrastructure\Comment\Query\CommentByCriteriaQuery;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

class InitiativeCommentedSender
{
    private MailerInterface $mailer;
    private TranslatorInterface $translator;
    private CommentByCriteriaQuery $query;

    public function __construct(MailerInterface     $mailer,
                                CommentByCriteriaQuery $query,
                                TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->query = $query;
    }

    public function send(InitiativeCommented $message): void
    {
        $this->query->tenant($message->company());
        $comment = $this->query->queryOne(new CommentByIdCriteria($message->comment()));

        if (!$comment) {
            return;
        }

        $followers = $comment->initiative()->followers()->active();

        /** @var Customer $customer */
        foreach ($followers->toArray() as $customer) {
            if ($customer->equals($comment->author())) {
                continue;
            }

            $language = $customer->activeLanguage()?->name() ?? ActiveLanguage::DEFAULT_LANGUAGE;

            $email = (new TemplatedEmail())
                ->to(new Address((string) $customer->email()))
                ->subject($this->translator->trans(
                    'Initiative commented',
                    [],
                    null,
                    $language
                ))
                ->htmlTemplate('/Initiative/Infrastructure/Comment/comment_created.html.twig')
                ->context([
                    'initiative' => $comment->initiative()->id(),
//                    'initiative' => $message->initiativeId(),
                    'comment' => $comment,
                    'url' => $message->company()->url(),
                    'language' => $language,
                ]);

            $this->mailer->send($email);
        }
    }
}