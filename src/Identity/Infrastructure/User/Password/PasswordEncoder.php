<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\User\Password;

use App\Identity\Application\Security\PasswordEncoder as PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;

final class PasswordEncoder implements PasswordEncoderInterface
{
    public function encodePassword(string $password): string
    {
        // NativePasswordEncoder isn't wired as service in IOC container, so instantiate it explicitly
        return (new NativePasswordEncoder())->encodePassword($password, null);
    }
}
