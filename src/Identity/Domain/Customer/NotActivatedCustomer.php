<?php

declare(strict_types=1);

namespace App\Identity\Domain\Customer;

use App\Identity\Domain\User\Email;
use DateTime;

class NotActivatedCustomer extends AbstractCustomer
{
//    private CustomerId $id;

//    private Email $email;

    private ?string $activation_token;

//    private Username $username;

//    private Password $password;

    public function __construct(CustomerId $id, Email $email, string $activationToken)
    {
        parent::__construct();

        $this->_setId($id);
        $this->_setEmail($email);
        $this->activation_token = $activationToken;
    }

    public function id(): CustomerId
    {
        return $this->_getId();
    }

    public function email(): Email
    {
        return $this->_getEmail();
    }

    public function token(): string
    {
        return $this->activation_token;
    }

    public function activate(): void
    {
        $this->activation_token = null;
    }

    public function created(): DateTime
    {
        return $this->_getCreated();
    }

//    public function activate(Username $username, Password $password): void
//    {
//        $this->setUname($username);
//        $this->setPassword($password);
//        $this->activation_token = null;
//    }
    public function username(): Username
    {
        return new Username((string) $this->_getId());
    }

    public function photo(): ?Photo
    {
        return $this->_getPhoto();
    }
}
