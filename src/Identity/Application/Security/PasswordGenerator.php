<?php

namespace App\Identity\Application\Security;

interface PasswordGenerator
{
    public function generate(): string;
}
