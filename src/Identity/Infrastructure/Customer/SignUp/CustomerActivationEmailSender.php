<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Customer\SignUp;

use App\Identity\Application\Customer\UseCase\SignUpCustomer\NotActivatedCustomerCreated;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CustomerActivationEmailSender
{
    private MailerInterface $mailer;
    private TranslatorInterface $translator;

    public function __construct(MailerInterface $mailer, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
    }

    public function send(NotActivatedCustomerCreated $message)
    {
        $language = $message->language()->language();
        $email = (new TemplatedEmail())
            ->to(new Address($message->customer()->email()->value()))
            ->subject($this->translator->trans(
                'Welcome to Kuntamatch!',
                [],
                null,
                $language
            ))
            ->htmlTemplate('/Identity/Infrastructure/Customer/SignUp/customer_activation.html.twig')
            ->context([
                'customer' => $message->customer(),
                'language' => $language,
                'url' => $message->url(),
            ]);

        $this->mailer->send($email);
    }
}
