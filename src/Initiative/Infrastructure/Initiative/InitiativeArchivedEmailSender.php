<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Initiative;

use App\Identity\Domain\Customer\Customer;
use App\Initiative\Application\Initiative\Query\InitiativeByCriteriaQuery;
use App\Initiative\Application\Initiative\Query\InitiativeByIdCriteria;
use App\Initiative\Application\Initiative\UseCase\ArchiveInitiative\InitiativeArchived;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

final class InitiativeArchivedEmailSender
{
    private MailerInterface $mailer;
    private TranslatorInterface $translator;
    private InitiativeByCriteriaQuery $query;

    public function __construct(InitiativeByCriteriaQuery $query, MailerInterface $mailer, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->query = $query;
    }

    public function send(InitiativeArchived $message)
    {
        $language = $message->language()->language();
        $this->query->tenant($message->company());
        $initiative = $this->query->queryOne(new InitiativeByIdCriteria($message->initiativeId()));
        $recipients = array_map(
            fn(Customer $customer) => new Address((string) $customer->email()),
            $initiative->followers()->active()->toArray(),
        );
        $email = (new TemplatedEmail())
            ->to(...$recipients)
            ->subject($this->translator->trans(
                'The initiative you are following has been archived',
                [],
                null,
                $language
            ))
            ->htmlTemplate('/Initiative/Infrastructure/Initiative/initiative_archived.html.twig')
            ->context([
                'title' => $message->title(),
                'language' => $language
            ]);

        $this->mailer->send($email);
    }
}
