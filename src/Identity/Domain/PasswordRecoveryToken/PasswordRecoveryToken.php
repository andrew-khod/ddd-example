<?php

declare(strict_types=1);

namespace App\Identity\Domain\PasswordRecoveryToken;

use App\Shared\Domain\BaseId;

class PasswordRecoveryToken
{
    private BaseId $user_id;

    private string $token;

    public function __construct(BaseId $userId, string $token)
    {
        $this->user_id = $userId;
        $this->token = $token;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function userId(): BaseId
    {
        return $this->user_id;
    }
}
