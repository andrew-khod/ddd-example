<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Customer\Photo;

use App\Identity\Application\Security\Security;
use App\Shared\Infrastructure\BaseImageManager;
use Symfony\Component\Filesystem\Filesystem;

class PhotoManager extends BaseImageManager
{
    private string $customerPhotoPath;

    public function __construct(string $customerPhotoPath,
                                string $kernelProjectDir,
                                string $projectPublicDir,
                                Security $security,
                                Filesystem $filesystem)
    {
        parent::__construct($kernelProjectDir, $projectPublicDir, $security, $filesystem);

        $this->customerPhotoPath = $customerPhotoPath;
    }

    protected function path(): string
    {
        return $this->customerPhotoPath;
    }
}
