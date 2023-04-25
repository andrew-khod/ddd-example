<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\SwitchLanguage;

use App\Identity\Application\Customer\CustomerEntityManager;
use App\Identity\Application\User\AuthenticatedCustomer;
use App\Shared\Application\AssignedToCompanyLanguagesQuery;
use App\Shared\Domain\Language;

final class SwitchLanguageHandler
{
    private AuthenticatedCustomer $authenticatedCustomer;
    private AssignedToCompanyLanguagesQuery $assignedToCompanyLanguagesQuery;
    private CustomerEntityManager $customerEntityManager;

    public function __construct(AuthenticatedCustomer           $authenticatedCustomer,
                                CustomerEntityManager $customerEntityManager,
                                AssignedToCompanyLanguagesQuery $assignedToCompanyLanguagesQuery)
    {
        $this->authenticatedCustomer = $authenticatedCustomer;
        $this->assignedToCompanyLanguagesQuery = $assignedToCompanyLanguagesQuery;
        $this->customerEntityManager = $customerEntityManager;
    }

    public function handle(SwitchLanguageCommand $command): void
    {
        $language = current(array_filter(
            $this->assignedToCompanyLanguagesQuery->query(),
            fn(Language $language) => $language->name() === $command->language()
        ));

        if (!$language) {
            return;
        }

        $this->authenticatedCustomer->user()->switchLanguage($language);
        $this->customerEntityManager->update();
    }
}
