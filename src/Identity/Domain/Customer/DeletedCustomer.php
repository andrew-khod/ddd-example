<?php

declare(strict_types=1);

namespace App\Identity\Domain\Customer;

use App\Identity\Domain\User\Email;

class DeletedCustomer extends AbstractCustomer
{
    public const TYPE = 'DeletedCustomer';

//    private CustomerId $id;
//    private string $email;
//    private ?string $username = null;
//    private ?string $password = null;
//    private ?string $firstname = null;
//    private ?string $lastname = null;
//    private ?DateTime $birthday = null;
//    private ?string $gender = null;
//    private ?string $postal = null;
//    private ?string $photo = null;

    public function __construct(CustomerId $id)
    {
        parent::__construct();

        $this->_setId($id);
        $this->_setEmail(new Email(sprintf('%s@mail.local', $id->value())));
//        $this->id = $id;
//        $this->email = sprintf('%s@mail.local', $id->value());
    }

    public function id()
    {
        return null;
    }

    public function username(): Username
    {
        return new Username((string) $this->_getEmail());
//        return $this->_getUsername();
    }

    public function photo(): ?Photo
    {
        return $this->_getPhoto();
    }
}
