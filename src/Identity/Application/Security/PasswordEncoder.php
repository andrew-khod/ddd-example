<?php

namespace App\Identity\Application\Security;

interface PasswordEncoder
{
    public function encodePassword(string $password): string;
}
