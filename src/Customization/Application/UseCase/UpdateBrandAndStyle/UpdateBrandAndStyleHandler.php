<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\UpdateBrandAndStyle;

use App\Identity\Application\Company\CompanyEntityManager;
use App\Identity\Application\User\AuthenticatedUser;
use App\Identity\Domain\Company\Name;
use App\Shared\Application\ActiveTenant;

final class UpdateBrandAndStyleHandler
{
    private ActiveTenant $tenant;
    private CompanyEntityManager $companyEntityManager;
    private AuthenticatedUser $authenticatedUser;

    public function __construct(ActiveTenant $tenant,
                                CompanyEntityManager $companyEntityManager,
                                AuthenticatedUser $authenticatedUser)
    {
        $this->tenant = $tenant;
        $this->companyEntityManager = $companyEntityManager;
        $this->authenticatedUser = $authenticatedUser;
    }

    public function handle(UpdateBrandAndStyleCommand $command): void
    {
        $company = $this->tenant->company();

        // todo for each field check if update indeed requested

        if ($command->name() && $command->name() !== (string) $company->name()) {
            $company->rename(new Name($command->name()));
        }

        if ($command->color() && $command->color() !== $company->color()) {
            $company->changeColor($command->color());
        }

        if ($command->footer() && $command->footer() !== $company->footer()) {
            $company->changeFooter($command->footer());
        }

        if ($this->authenticatedUser->user()->isSuperAdmin()) {
            if ($command->alias() && $command->alias() !== $company->alias()) {
                $company->changeAlias($command->alias());
            }

            if ($command->url() && $command->url() !== $company->url()) {
                $company->changeUrl($command->url());
            }
        }

        $this->companyEntityManager->update();
    }
}
