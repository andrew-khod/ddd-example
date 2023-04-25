<?php

declare(strict_types=1);

namespace App\Identity\Application\Security;

use App\Identity\Domain\User\BaseUser;
use App\Identity\Domain\User\Password as UserPassword;
use App\Identity\Domain\User\PasswordRecoveryToken;

final class Security
{
    private PasswordGenerator $passwordGenerator;

    private PasswordEncoder $passwordEncoder;

    private PasswordRecoveryTokenEncoder $passwordRecoveryTokenEncoder;

    public function __construct(PasswordGenerator $passwordGenerator,
                                PasswordEncoder $passwordEncoder,
                                PasswordRecoveryTokenEncoder $passwordRecoveryTokenEncoder)
    {
        $this->passwordGenerator = $passwordGenerator;
        $this->passwordEncoder = $passwordEncoder;
        $this->passwordRecoveryTokenEncoder = $passwordRecoveryTokenEncoder;
    }

    public function randomPassword(): Password
    {
        $raw = $this->passwordGenerator->generate();
        $hash = $this->passwordEncoder->encodePassword($raw);

        return new Password($raw, $hash);
    }

    public function passwordRecoveryToken(): PasswordRecoveryToken
    {
        $raw = $this->passwordGenerator->generate();
        $hash = $this->passwordRecoveryTokenEncoder->encode($raw);

        return new PasswordRecoveryToken($hash);
    }

    public function changeUserPassword(BaseUser $user, string $password): void
    {
        $password = $this->passwordEncoder->encodePassword($password);
        $password = new UserPassword($password);

        $user->changePassword($password);
    }
}
