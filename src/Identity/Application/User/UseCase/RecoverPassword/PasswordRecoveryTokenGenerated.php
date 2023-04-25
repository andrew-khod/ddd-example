<?php

namespace App\Identity\Application\User\UseCase\RecoverPassword;

use App\Identity\Domain\PasswordRecoveryToken\PasswordRecoveryToken;
use App\Identity\Domain\User\BaseUser;
use App\Shared\Application\AsyncMessage;
use App\Shared\Domain\ActiveLanguage;

final class PasswordRecoveryTokenGenerated implements AsyncMessage
{
    private BaseUser $user;
    private PasswordRecoveryToken $token;
    private ?string $language = null;
    private ?string $url = null;

    public function __construct(BaseUser $user,
                                PasswordRecoveryToken $token,
                                ActiveLanguage $activeLanguage = null,
                                string $url = null)
    {
        $this->user = $user;
        $this->token = $token;
        $this->language = $activeLanguage?->language();
        $this->url = $url;
    }

    public function user(): BaseUser
    {
        return $this->user;
    }

    public function token(): PasswordRecoveryToken
    {
        return $this->token;
    }

    public function language(): ?string
    {
        return $this->language;
    }

    public function url(): ?string
    {
        return $this->url;
    }
}
