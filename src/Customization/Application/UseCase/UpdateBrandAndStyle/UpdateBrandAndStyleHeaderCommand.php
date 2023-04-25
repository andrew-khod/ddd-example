<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\UpdateBrandAndStyle;

use App\Shared\Domain\PreUploadedImage\PreUploadedImage;

final class UpdateBrandAndStyleHeaderCommand
{
    private ?PreUploadedImage $header;
    private ?PreUploadedImage $logo;
    private ?PreUploadedImage $secondLogo;

    public function __construct(?PreUploadedImage $header,
                                ?PreUploadedImage $logo,
                                ?PreUploadedImage $secondLogo)
    {
        $this->header = $header;
        $this->logo = $logo;
        $this->secondLogo = $secondLogo;
    }

    public function header(): ?PreUploadedImage
    {
        return $this->header;
    }

    public function logo(): ?PreUploadedImage
    {
        return $this->logo;
    }

    public function secondLogo(): ?PreUploadedImage
    {
        return $this->secondLogo;
    }
}
