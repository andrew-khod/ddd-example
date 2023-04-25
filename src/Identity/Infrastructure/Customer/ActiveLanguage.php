<?php

namespace App\Identity\Infrastructure\Customer;

use App\Shared\Domain\ActiveLanguage as ActiveLanguageInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ActiveLanguage implements ActiveLanguageInterface
{
    public const DEFAULT_LANGUAGE = 'fi';

    private string $request;

    public function __construct(RequestStack $request)
    {
        $lang = null;

        if ($request->getCurrentRequest()) {
            $lang = locale_parse($request->getCurrentRequest()->getPreferredLanguage())['language'];
        }

        $this->request = $lang ?? self::DEFAULT_LANGUAGE;
    }

    public function language(): ?string
    {
        return $this->request;
    }
}