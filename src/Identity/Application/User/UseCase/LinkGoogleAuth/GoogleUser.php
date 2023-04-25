<?php

declare(strict_types=1);

namespace App\Identity\Application\User\UseCase\LinkGoogleAuth;

use App\Identity\Domain\AuthVendor\AuthVendor;
use App\Identity\Domain\User\BaseUser;

final class GoogleUser
{
    private const TYPE = 'google';

    private string $id;
    private string $firstname;
    private string $lastname;
    private string $email;

    public function __construct(string $id,
                                string $firstname,
                                string $lastname,
                                string $email)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function toAuthVendor(BaseUser $user): AuthVendor
    {
        return new AuthVendor(
            $this->id(),
            self::TYPE,
            $user);
    }

    public function email(): string
    {
        return $this->email;
    }

    public function firstname(): string
    {
        return $this->firstname;
    }

    public function lastname(): string
    {
        return $this->lastname;
    }
}
