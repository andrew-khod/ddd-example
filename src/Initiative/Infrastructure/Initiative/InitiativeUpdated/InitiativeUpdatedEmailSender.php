<?php

namespace App\Initiative\Infrastructure\Initiative\InitiativeUpdated;

use App\Identity\Domain\Customer\Customer;
use App\Initiative\Application\Initiative\Query\InitiativeByCriteriaQuery;
use App\Initiative\Application\Initiative\Query\InitiativeByIdCriteria;
use App\Initiative\Application\Initiative\UseCase\UpdateInitiative\InitiativeUpdated;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

final class InitiativeUpdatedEmailSender
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

    public function send(InitiativeUpdated $message)
    {
        $language = $message->language()->language();
        $this->query->tenant($message->company());
        $initiative = $this->query->queryOne(new InitiativeByIdCriteria($message->initiativeId()));
        $recipients = array_map(
            fn(Customer $customer) => new Address($customer->email()),
            $initiative->followers()->active()->toArray(),
        );
        $email = (new TemplatedEmail())
            ->to(...$recipients)
            ->subject($this->translator->trans(
                'The initiative you are following has changed',
                [],
                null,
                $language
            ))
            ->htmlTemplate('/Initiative/Infrastructure/Initiative/InitiativeUpdated/initiative_updated.html.twig')
            ->context([
                'changes' => $message->changes(),
                'language' => $language,
                'id' => $message->initiativeId(),
                'url' => $message->company()->url(),
            ]);

        $this->mailer->send($email);
    }
}