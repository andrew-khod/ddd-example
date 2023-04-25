<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

abstract class AbstractExceptionSubscriber implements EventSubscriberInterface
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $errors = [];

        if (!$this->supports($exception)) {
            return;
        }

        $errors = $this->getErrors($exception);

        foreach ($errors as $key => $value) {
            $errors[$key] = $this->translator->trans($value);
        }

        $response = new JsonResponse([
            'errors' => $errors,
        ]);
        $response->setStatusCode($exception->getCode());

        $event->setResponse($response);
    }

    abstract protected function supports(Throwable $exception): bool;

    abstract protected function getErrors(Throwable $exception): array;
}
