<?php

declare(strict_types=1);

namespace App\Identity\Domain\Customer;

use App\Identity\Domain\PasswordRecoveryToken\PasswordRecoveryToken;
use App\Identity\Domain\Role\RoleCollection;
use App\Identity\Domain\User\BaseUser;
use App\Identity\Domain\User\Email;
use App\Identity\Domain\User\Password;
use App\Initiative\Domain\Comment\CommentCollection;
use App\Initiative\Domain\Event\Event;
use App\Initiative\Domain\Event\EventReadStatus;
use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Initiative\InitiativeCollection;
use App\Shared\Domain\Language;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Customer extends AbstractCustomer implements BaseUser, BaseCustomer
{
//    private CustomerId $id;
//
//    // TODO move $firstname & $lastname to one prop Customer\Name $name
//    // TODO think about to move profile related fields to CustomerProfile
//    private ?string $firstname = null;
//
//    private ?string $lastname = null;
//
//    private ?DateTime $birthday = null;
//
//    private ?string $gender = null;
//
//    private ?string $postal = null;
//
//    private Username $username;
//
//    private Email $email;
//
//    private Password $password;
//
//    private ?string $password_recovery_token = null;
//
//    private Collection $initiatives;
//
//    private ?string $photo = null;

    private ?Language $activeLanguage = null;

    public const TYPE = 'Customer';

    public function __construct(CustomerId $id,
                                Email $email,
                                Password $password,
                                Username $username,
                                ?Name $name = null)
    {
        parent::__construct();

        $this->_setId($id);
        $this->_setEmail($email);
        $this->_setPassword($password);
        $this->_setUsername($username);
        $this->_setName($name);
//        $this->lastname = $name->lastname();
//        $this->initiatives = new ArrayCollection();

        //todo should we move it to AbstractCustomer?
        $this->eventsRead = new ArrayCollection();
    }

    public function id(): CustomerId
    {
        return $this->_getId();
    }

    public function email(): Email
    {
        return $this->_getEmail();
    }

    public function name(): ?Name
    {
        // TODO allow name to be nullable
        return $this->_getName();
    }

    public function username(): Username
    {
        return $this->_getUsername();
    }

    public function changePassword(Password $password): void
    {
        $this->_setPassword($password);
    }

    public function availableRoles(RoleCollection $roles): void
    {
        // TODO: Implement availableRoles() method.
    }

    public function getRoles()
    {
        return [];
    }

    // TODO Customer invariant must return valid password, so returning null is a hack;
    // TODO issue arise when we trying to get the password of NotActivatedCustomer using CustomerIdentityRepo
    // TODO so filter only through active customers while using CustomerIdentityRepo
    // TODO and filter only inactive customers while using NotActivatedCustomerIdentityRepo
    public function getPassword()
    {
        return $this->_getPassword() ?? null;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        return $this->_getEmail()->value();
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function passwordRecoveryToken(): ?PasswordRecoveryToken
    {
        return $this->_getPasswordRecoveryToken();
    }

    public function updatePasswordRecoveryToken(PasswordRecoveryToken $passwordRecoveryToken): void
    {
        $this->_setPasswordRecoveryToken($passwordRecoveryToken);
    }

//    public function updateGeneralInformation(GeneralInformation $information): void
//    {
//        if ($information->username()) {
//            $this->username = $information->username();
//        }
//
//        if ($information->birthday()) {
//            $this->birthday = $information->birthday()->value();
//        }
//
//        if ($information->gender()) {
//            $this->gender = (string) $information->gender();
//        }
//
//        if ($information->postal()) {
//            $this->postal = (string) $information->postal();
//        }
//
//        if ($information->photo()) {
//            $this->photo = $information->photo();
//        }
//    }

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

    public function rename(Username $username): void
    {
        $this->_setUsername($username);
    }

    public function updatePhoto(?Photo $photo): void
    {
        $this->_setPhoto($photo);
    }

    public function updateBirthday(?Birthday $birthday): void
    {
        $this->_setBirthday($birthday);
    }

    public function updateGender(?Gender $gender): void
    {
        $this->_setGender($gender);
    }

    public function updatePostal(?Postal $postal): void
    {
        $this->_setPostal($postal);
    }

    public function initiatives(): InitiativeCollection
    {
        return new InitiativeCollection(...$this->_getInitiatives()->toArray());
    }

    public function participation(): InitiativeCollection
    {
        return new InitiativeCollection(...$this->_getParticipation()->toArray());
    }

    public function following(): InitiativeCollection
    {
        return new InitiativeCollection(...$this->_getFollowing()->map(
            fn(Following $following) => $following->initiative()
        )->toArray());
//        return new InitiativeCollection(...$this->_getFollowing()->toArray());
    }

//    public function deactive(): NotActivatedCustomer
////    public function deactive(): void
//    {
//        return new NotActivatedCustomer($this->id, $this->email(), 'asdasd');
////        $this->username = null;
////        $this->firstname = null;
////        $this->lastname = null;
////        $this->birthday = null;
////        $this->gender = null;
////        $this->postal = null;
////        $this->photo = null;
////        TODO OR
////        return new NotActivatedCustomer()
//    }

//    public function join(Initiative $initiative): void
//    {
//        $this->_getParticipation()->add($initiative);
//    }

    public function comments(): CommentCollection
    {
        return new CommentCollection(...$this->_getComments()->toArray());
    }

    public function favourite(Initiative $initiative): void
    {
        $this->_getFavourites()->add($initiative);
    }

    public function isFavourite(Initiative $initiative): bool
    {
        return $this->_getFavourites()->contains($initiative);
    }

    public function removeFavourite(Initiative $initiative): void
    {
        $this->_getFavourites()->removeElement($initiative);
    }

    public function created(): DateTime
    {
        return $this->_getCreated();
    }

    public function switchLanguage(Language $language): void
    {
        $this->activeLanguage = $language;
    }

    public function activeLanguage(): ?Language
    {
        return $this->activeLanguage;
    }

    public function favourites(): InitiativeCollection
    {
        return new InitiativeCollection(...$this->_getFavourites()->toArray());
    }

    public function archiveCreatedInitiatives(): void
    {
        foreach ($this->_getInitiatives() as $initiative) {
            $initiative->archive();
        }
    }

    public function hasReadNotification(Event $event): bool
    {
        return $this->eventsRead
            ->map(fn(EventReadStatus $status) => $status->event())
            ->contains($event);
//        return $this->notificationsRead()->contains($event);
    }

////    public function notificationsRead(): array
//    public function notificationsRead(): Collection
//    {
//        return $this->eventsRead->map(fn(EventReadStatus $status) => $status->event());
////        return array_map(
////            fn(EventReadStatus $status) => $status->event(),
////            $this->eventsRead->toArray()
////        );
//    }

    public function hideAllNotifications(): void
    {
        $this->eventsRead->map(fn(EventReadStatus $status) => $status->hide());
    }
}
