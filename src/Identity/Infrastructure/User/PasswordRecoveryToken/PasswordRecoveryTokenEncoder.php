<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\User\PasswordRecoveryToken;

use App\Identity\Application\Security\PasswordRecoveryTokenEncoder as PasswordRecoveryTokenEncoderInterface;

final class PasswordRecoveryTokenEncoder implements PasswordRecoveryTokenEncoderInterface
{
    public function encode(string $raw): string
    {
        return md5($raw);
    }
}
