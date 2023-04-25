<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\DeleteCustomer;

use App\Identity\Application\Customer\CustomerEntityManager;
use App\Identity\Application\User\AuthenticatedCustomer;
use App\Identity\Domain\Customer\DeletedCustomer;
use App\Shared\Application\ActiveTenant;
use App\Shared\Application\ImageManager;
use App\Shared\Application\MessageBus;

final class DeleteCustomerHandler
{
    private CustomerEntityManager $customerEntityManager;
    private AuthenticatedCustomer $authenticatedCustomer;
    private ImageManager $imageManager;
    private MessageBus $messageBus;
//    private ActiveLanguage $activeLanguage;
    private ActiveTenant $tenant;

    public function __construct(AuthenticatedCustomer $authenticatedCustomer,
                                CustomerEntityManager $customerEntityManagerByActiveTenant,
                                MessageBus $messageBus,
//                                ActiveLanguage $activeLanguage,
                                ActiveTenant $tenant,
                                ImageManager $customerPhotoManager)
    {
        $this->customerEntityManager = $customerEntityManagerByActiveTenant;
        $this->authenticatedCustomer = $authenticatedCustomer;
        $this->imageManager = $customerPhotoManager;
        $this->messageBus = $messageBus;
//        $this->activeLanguage = $activeLanguage;
        $this->tenant = $tenant;
    }

    public function handle(): void
    {
        // TODO verify password against XSS/CSRF attacks
        $customer = $this->authenticatedCustomer->user();

        $photo = (string) $customer->photo();

//        $deletedCustomer = $customer->deactive();

        $initiatives = $customer->initiatives()->toIDs();
        $customer->archiveCreatedInitiatives();

        $deletedCustomer = new DeletedCustomer($customer->id());

        $this->customerEntityManager->sync($deletedCustomer);
        $this->customerEntityManager->update();
        $this->customerEntityManager->updateInheritanceType($customer, DeletedCustomer::TYPE);

        if ($photo) {
            $this->imageManager->remove($photo);
        }

        $this->messageBus->dispatch(new InitiativesArchived(
            $this->tenant->company(),
//            $this->activeLanguage,
            ...$initiatives,
        ));
    }
}
