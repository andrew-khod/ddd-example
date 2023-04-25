<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\UpdateBrandAndStyle;

use App\Identity\Application\Company\CompanyEntityManager;
use App\Shared\Application\ActiveTenant;
use App\Shared\Application\ImageManager;
use App\Shared\Domain\PreUploadedImage\PreUploadedImageCollection;

final class UpdateBrandAndStyleHeaderHandler
{
    private ImageManager $headerImageManager;
    private ActiveTenant $tenant;
    private CompanyEntityManager $companyEntityManager;
    private string $headerImagePath;
    private string $logoImagePath;
    private ImageManager $logoImageManager;

    public function __construct(ImageManager $headerImageManager,
                                ImageManager $logoImageManager,
                                ActiveTenant $tenant,
                                string $headerImagePath,
                                string $logoImagePath,
                                CompanyEntityManager $companyEntityManager)
    {
        $this->headerImageManager = $headerImageManager;
        $this->tenant = $tenant;
        $this->companyEntityManager = $companyEntityManager;
        $this->headerImagePath = $headerImagePath;
        $this->logoImagePath = $logoImagePath;
        $this->logoImageManager = $logoImageManager;
    }

    public function handle(UpdateBrandAndStyleHeaderCommand $command): void
    {
        $company = $this->tenant->company();

        if ($command->header()) {
            if ($company->header()) {
                $this->headerImageManager->remove(sprintf('%s/%s', $this->headerImagePath, $company->header()));
            }

            $header = $this->headerImageManager->handle(new PreUploadedImageCollection($command->header()));
            $company->changeHeader($header->toArray()[0]->name());
        }

        if ($command->logo()) {
            if ($company->logo()) {
                $this->logoImageManager->remove(sprintf('%s/%s', $this->logoImagePath, $company->logo()));
            }

            $logo = $this->logoImageManager->handle(new PreUploadedImageCollection($command->logo()));
            $company->changeLogo($logo->toArray()[0]->name());
        }

        if ($command->secondLogo()) {
            if ($company->secondLogo()) {
                $this->logoImageManager->remove(sprintf('%s/%s', $this->logoImagePath, $company->secondLogo()));
            }

            $secondLogo = $this->logoImageManager->handle(new PreUploadedImageCollection($command->secondLogo()));
            $company->changeSecondLogo($secondLogo->toArray()[0]->name());
        }

        $this->companyEntityManager->update();
    }
}
