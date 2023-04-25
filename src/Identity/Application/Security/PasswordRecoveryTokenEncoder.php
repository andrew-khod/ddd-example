<?php

namespace App\Identity\Application\Security;

interface PasswordRecoveryTokenEncoder
{
    public function encode(string $raw): string;
}
