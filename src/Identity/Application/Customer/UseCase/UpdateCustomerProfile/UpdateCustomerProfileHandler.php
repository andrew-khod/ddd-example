<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\UpdateCustomerProfile;

use App\Identity\Application\Customer\CustomerEntityManager;
use App\Identity\Application\Customer\Query\CustomerByCriteriaQuery;
use App\Identity\Application\Customer\Query\CustomerByUsernameCriteria;
use App\Identity\Application\Security\Security;
use App\Identity\Application\User\AuthenticatedCustomer;
use App\Identity\Application\User\Exception\UserException;
use App\Identity\Domain\Customer\Birthday;
use App\Identity\Domain\Customer\Customer;
use App\Identity\Domain\Customer\Gender;
use App\Identity\Domain\Customer\Photo;
use App\Identity\Domain\Customer\Postal;
use App\Identity\Domain\Customer\Username;
use App\Shared\Application\ImageManager;

final class UpdateCustomerProfileHandler
{
    private AuthenticatedCustomer $authenticatedCustomer;
    private CustomerEntityManager $customerEntityManager;
    private CustomerByCriteriaQuery $customerByCriteriaQuery;
    private ImageManager $imageManager;
    private Security $security;

    public function __construct(AuthenticatedCustomer $authenticatedCustomer,
                                CustomerByCriteriaQuery $customerByCriteriaQuery,
                                CustomerEntityManager $customerEntityManager,
                                Security $security,
                                ImageManager $customerPhotoManager)
    {
        $this->authenticatedCustomer = $authenticatedCustomer;
        $this->customerEntityManager = $customerEntityManager;
        $this->customerByCriteriaQuery = $customerByCriteriaQuery;
        $this->imageManager = $customerPhotoManager;
        $this->security = $security;
    }

    public function handle(UpdateCustomerProfileCommand $command): Customer
    {
        $customer = $this->authenticatedCustomer->user();
        $birthday = $command->birthday() ? new Birthday($command->birthday()) : null;
        $gender = $command->gender() ? new Gender($command->gender()) : null;
        $postal = $command->postal() ? new Postal($command->postal()) : null;

        if ($command->isUsernameUpdateRequested()) {
            $username = $this->handleUsername($command->username());
            $customer->rename($username);
        }

        if ($command->isPasswordUpdateRequested()) {
            $this->security->changeUserPassword($customer, $command->password());
        }

        if ($command->isBirthdayUpdateRequested()) {
            $customer->updateBirthday($birthday);
        }

        if ($command->isGenderUpdateRequested()) {
            $customer->updateGender($gender);
        }

        if ($command->isPostalUpdateRequested()) {
            $customer->updatePostal($postal);
        }

        if ($command->isPhotoUpdateRequested()) {
            $this->handlePhoto($command, $customer);
        }

        // TODO get GeneralInformation aka CustomerProfile like $customer->generalInformation()
        // TODO update GeneralInformation Value Object and set to customer like $customer->updateGeneralInformation()

//        $information = new GeneralInformation(
//            $username,
//            $birthday,
//            $gender,
//            $postal,
//            $photo,
//        );
//
//        $customer->updateGeneralInformation($information);
        $this->customerEntityManager->update();

        return $customer;
    }

    private function handleUsername(string $username): Username
    {
        $username = new Username($username);
        $criteria = new CustomerByUsernameCriteria($username);
        $customer = $this->customerByCriteriaQuery->queryOne($criteria);

        if ($customer && !$customer->equals($this->authenticatedCustomer->user())) {
            throw UserException::usernameAlreadyExist();
        }

        return $username;
    }

    private function handlePhoto(UpdateCustomerProfileCommand $command, Customer $customer): void
    {
        // TODO save the file physically AFTER saving the customer
        $oldPhoto = (string) $customer->photo();

        $photo = $command->photo();

        if ($photo) {
            $photo = $this->imageManager->handle($photo);
            $photo = new Photo($photo->get(0)->name());
        }

        $customer->updatePhoto($photo);

        if ($oldPhoto) {
            $this->imageManager->remove($oldPhoto);
        }
    }
}
