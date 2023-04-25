<?php

namespace App\Identity\Domain\Customer;

use App\Identity\Domain\PasswordRecoveryToken\PasswordRecoveryToken;
use App\Identity\Domain\User\Email;
use App\Identity\Domain\User\Password;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

abstract class AbstractCustomer implements BaseCustomer
{
    protected Collection $eventsRead;
    private ?CustomerId $id = null;

    // TODO move $firstname & $lastname to one prop Customer\Name $name
    // TODO think about to move profile related fields to CustomerProfile
    private ?string $firstname = null;

    private ?string $lastname = null;

    private ?DateTime $birthday = null;

    private ?string $gender = null;

    private ?string $postal = null;

    private ?Username $username = null;

    private ?Email $email = null;

    private ?Password $password = null;

    private ?string $password_recovery_token = null;

    private Collection $initiatives;

    private Collection $participation;
    private Collection $following;

    private Collection $comments;

    private Collection $favourites;

    private ?string $photo = null;
    private ?DateTime $created = null;

    public function __construct()
    {
        $this->initiatives = new ArrayCollection([]);
        $this->participation = new ArrayCollection([]);
        $this->following = new ArrayCollection([]);
        $this->favourites = new ArrayCollection([]);
        $this->comments = new ArrayCollection([]);
        $this->created = new DateTime();
    }

    protected function _getName(): ?Name
    {
        return $this->firstname && $this->lastname
            ? new Name($this->firstname, $this->lastname)
            : null;
    }

    protected function _getPasswordRecoveryToken(): ?PasswordRecoveryToken
    {
        return $this->password_recovery_token
            ? new PasswordRecoveryToken($this->id, $this->password_recovery_token)
            : null;
    }

    protected function _setPasswordRecoveryToken(PasswordRecoveryToken $passwordRecoveryToken): void
    {
        $this->password_recovery_token = $passwordRecoveryToken->token();
    }

    protected function _getBirthday(): ?Birthday
    {
        return $this->birthday
            ? new Birthday($this->birthday)
            : null;
    }

    protected function _getPostal(): ?Postal
    {
        return $this->postal
            ? new Postal($this->postal)
            : null;
    }

    protected function _getGender(): ?Gender
    {
        return $this->gender
            ? new Gender($this->gender)
            : null;
    }

    protected function _getPhoto(): ?Photo
    {
        return $this->photo
            ? new Photo($this->photo)
            : null;
    }

    protected function _getId(): ?CustomerId
    {
        return $this->id;
    }

    protected function _setId(CustomerId $id): void
    {
        $this->id = $id;
    }

    protected function _getEmail(): ?Email
    {
        return $this->email;
    }

    protected function _setEmail(Email $email): void
    {
        $this->email = $email;
    }

    protected function _getUsername(): ?Username
    {
        return $this->username;
    }

    protected function _setUsername(Username $username): void
    {
        $this->username = $username;
    }

    protected function _getPassword(): ?Password
    {
        return $this->password;
    }

    protected function _setPassword(Password $password): void
    {
        $this->password_recovery_token = null;
        $this->password = $password;
    }

    protected function _setName(?Name $name): void
    {
        if (!$name) {
            $this->firstname = null;
            $this->lastname = null;
            
            return;
        }

        $this->firstname = $name->firstname();
        $this->lastname = $name->lastname();
    }

    protected function _setPhoto(?Photo $photo): void
    {
        $this->photo = $photo ? (string) $photo : null;
    }

    protected function _setBirthday(?Birthday $birthday): void
    {
        $this->birthday = $birthday ? $birthday->value() : null;
    }

    protected function _setGender(?Gender $gender): void
    {
        $this->gender = $gender ? $gender->value() : null;
    }

    protected function _setPostal(?Postal $postal): void
    {
        $this->postal = $postal ? $postal->value() : null;
    }

    protected function _getInitiatives(): Collection
    {
        return $this->initiatives;
    }

    protected function _getParticipation(): Collection
    {
        return $this->participation;
    }

    protected function _getFollowing(): Collection
    {
        return $this->following;
    }

    protected function _getComments(): Collection
    {
        return $this->comments;
    }

    protected function _getFavourites(): Collection
    {
        return $this->favourites;
    }

    protected function _getCreated(): DateTime
    {
        return $this->created;
    }

    public function equals(BaseCustomer $customer): bool
    {
        //fixme we have DeletedCustomer
        if ($customer->id()) {
            return $this->_getId()->equals($customer->id());
        }

        return false;
    }

    abstract public function username(): Username;
    abstract public function photo(): ?Photo;
}
