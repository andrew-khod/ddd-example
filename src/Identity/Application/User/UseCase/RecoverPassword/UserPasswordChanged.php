<?php

namespace App\Identity\Application\User\UseCase\RecoverPassword;

use App\Identity\Domain\User\BaseUser;
use App\Shared\Application\AsyncMessage;
use App\Shared\Domain\ActiveLanguage;

final class UserPasswordChanged implements AsyncMessage
{
    private BaseUser $user;
    private ?string $language;

    public function __construct(BaseUser $user, ActiveLanguage $language = null)
    {
        $this->user = $user;
        $this->language = $language?->language();
    }

    public function user(): BaseUser
    {
        return $this->user;
    }

    public function language(): ?string
    {
        return $this->language;
    }
}
