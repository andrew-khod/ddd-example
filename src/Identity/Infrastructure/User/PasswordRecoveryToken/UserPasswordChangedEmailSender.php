<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\User\PasswordRecoveryToken;

use App\Identity\Application\User\UseCase\RecoverPassword\UserPasswordChanged;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UserPasswordChangedEmailSender
{
    private MailerInterface $mailer;
    private TranslatorInterface $translator;

    public function __construct(MailerInterface $mailer, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
    }

    public function send(UserPasswordChanged $message)
    {
        $user = $message->user();

        $email = (new TemplatedEmail())
            ->to(new Address($user->email()->value()))
            ->subject($this->translator->trans(
                'Password changed',
                [],
                null,
                $message->language()
            ))
            ->htmlTemplate('/Identity/Infrastructure/User/PasswordRecoveryToken/password_changed.html.twig')
            ->context([
                'user' => $user,
                'language' => $message->language(),
            ]);

        $this->mailer->send($email);
    }
}
