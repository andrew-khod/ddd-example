<?php

namespace App\Identity\Infrastructure\User\Security\UserCreated;

use App\Identity\Application\User\UseCase\CreateUser\UserCreated;
use App\Identity\Infrastructure\Customer\ActiveLanguage;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UserCreatedEmailSender
{
    private MailerInterface $mailer;
    private TranslatorInterface $translator;
    private string $dashboardUIUrl;

    public function __construct(MailerInterface $mailer,
                                TranslatorInterface $translator,
                                string $dashboardUIUrl)
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->dashboardUIUrl = $dashboardUIUrl;
    }

    public function send(UserCreated $message)
    {
//        $language = $message->language()->language();
        $language = ActiveLanguage::DEFAULT_LANGUAGE;
        $email = (new TemplatedEmail())
            ->to(new Address($message->user()->email()))
            ->subject($this->translator->trans(
                'Welcome to Kuntamatch!',
                [],
                null,
                $language
            ))
            ->htmlTemplate('/Identity/Infrastructure/User/Security/UserCreated/password.html.twig')
            ->context([
                'user' => $message->user(),
                'token' => $message->token(),
                'company' => $message->company(),
                'language' => $language,
                'url' => $this->dashboardUIUrl,
            ]);

        $this->mailer->send($email);
    }
}