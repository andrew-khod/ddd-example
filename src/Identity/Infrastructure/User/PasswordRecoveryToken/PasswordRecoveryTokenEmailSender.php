<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\User\PasswordRecoveryToken;

use App\Identity\Application\User\UseCase\RecoverPassword\PasswordRecoveryTokenGenerated;
use App\Identity\Domain\Customer\Customer;
use App\Identity\Domain\User\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

final class PasswordRecoveryTokenEmailSender
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

    public function send(PasswordRecoveryTokenGenerated $message)
    {
        $user = $message->user();
        $token = $message->token();
        $url = $template = null;

//        $template = match (get_class($user)) {
//            User::class => '/Identity/Infrastructure/User/PasswordRecoveryToken/password_recovery_token.html.twig',
//            Customer::class => '/Identity/Infrastructure/Customer/RecoverPassword/password_recovery_token.html.twig',
//            default => false,
//        };

        // todo think about Sender separation for User/Customer to avoid optional params like $url for $message
//        switch (get_class($user)) {
//            case User::class:
//                $template = '/Identity/Infrastructure/User/PasswordRecoveryToken/password_recovery_token.html.twig';
//                $url = $this->dashboardUIUrl;
//                break;
//            case Customer::class && $message->url():
//                $template = '/Identity/Infrastructure/Customer/RecoverPassword/password_recovery_token.html.twig';
//                $url = $message->url();
//                break;
//        }

        if ($user instanceof User) {
            $template = '/Identity/Infrastructure/User/PasswordRecoveryToken/password_recovery_token.html.twig';
            $url = $this->dashboardUIUrl;
        }

        if ($user instanceof Customer) {
            $template = '/Identity/Infrastructure/Customer/RecoverPassword/password_recovery_token.html.twig';
            $url = $message->url();
        }

        $email = (new TemplatedEmail())
            ->to(new Address($user->email()->value()))
            ->subject($this->translator->trans(
                'Forgot your password?',
                [],
                null,
                $message->language()
            ))
            ->htmlTemplate($template)
            ->context([
                'token' => $token->token(),
                'language' => $message->language(),
                'url' => $url,
            ]);

        $this->mailer->send($email);
    }
}
