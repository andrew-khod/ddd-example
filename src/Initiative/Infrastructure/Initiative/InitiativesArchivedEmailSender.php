<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Initiative;

use App\Identity\Application\Customer\UseCase\DeleteCustomer\InitiativesArchived;
use App\Identity\Domain\Customer\Customer;
use App\Identity\Infrastructure\Customer\ActiveLanguage;
use App\Initiative\Application\Initiative\Query\InitiativeByCriteriaQuery;
use App\Initiative\Application\Initiative\Query\InitiativeByIdCriteria;
use App\Initiative\Domain\Initiative\Initiative;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

final class InitiativesArchivedEmailSender
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

//    private InitiativeArchivedEmailSender $sender;
//
//    public function __construct(InitiativeArchivedEmailSender $sender)
//    {
//        $this->sender = $sender;
//    }

    public function send(InitiativesArchived $message)
    {
//        // fixme N+1 queries generated when reusing sender. consider rewriting this block
//        array_map(function (InitiativeId $id) use ($message) {
//            $this->sender->send(new InitiativeArchived($message->company(), 'asd', $message->language()));
//            return 1;
//        }, $message->initiatives());

        $this->query->tenant($message->company());

        $initiatives = $this->query->queryMultipleV2(new InitiativeByIdCriteria(...$message->initiatives()));

        array_map(function (Initiative $initiative) {
            /** @var Customer $follower */
            foreach ($initiative->followers()->active()->toArray() as $follower) {
                $language = $follower->activeLanguage()?->name() ?? ActiveLanguage::DEFAULT_LANGUAGE;
                $email = (new TemplatedEmail())
                    ->to(new Address((string) $follower->email()))
                    ->subject($this->translator->trans(
                        'The initiative you are following has been archived',
                        [],
                        null,
                        $language
                    ))
                    ->htmlTemplate('/Initiative/Infrastructure/Initiative/initiative_archived.html.twig')
                    ->context([
                        'title' => $initiative->briefing()->title(),
                        'language' => $language
                    ]);

                $this->mailer->send($email);
            }
        }, $initiatives->toArray());
    }
}
