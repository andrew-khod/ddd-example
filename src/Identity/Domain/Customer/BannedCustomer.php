<?php

declare(strict_types=1);

namespace App\Identity\Domain\Customer;

use App\Identity\Domain\User\Email;
use DateTime;

class BannedCustomer extends AbstractCustomer
{
    public const TYPE = 'BannedCustomer';

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
//        $this->_setEmail(new Email(sprintf('%s@mail.local', $id->value())));
//        $this->id = $id;
//        $this->email = sprintf('%s@mail.local', $id->value());
    }

    public function id(): CustomerId
    {
        return $this->_getId();
    }

    public function email(): Email
    {
        return $this->_getEmail();
    }

    public function username(): Username
    {
        return $this->_getUsername();
    }

    public function birthday(): ?Birthday
    {
        return $this->_getBirthday();
    }

    public function postal(): ?Postal
    {
        return $this->_getPostal();
    }

    public function gender(): ?Gender
    {
        return $this->_getGender();
    }

    public function photo(): ?Photo
    {
        return $this->_getPhoto();
    }

    public function created(): DateTime
    {
        return $this->_getCreated();
    }
}
