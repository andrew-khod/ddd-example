<?php

declare(strict_types=1);

namespace App\Identity\Application\User\UseCase\LinkGoogleAuth;

final class LinkGoogleAuthCommand
{
    private string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function code(): string
    {
        return $this->code;
    }
}
