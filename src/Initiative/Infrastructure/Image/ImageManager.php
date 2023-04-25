<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Image;

use App\Identity\Application\Security\Security;
use App\Shared\Infrastructure\BaseImageManager;
use Symfony\Component\Filesystem\Filesystem;

class ImageManager extends BaseImageManager
{
    private string $initiativeImagesPath;

    public function __construct(string $initiativeImagesPath,
                                string $kernelProjectDir,
                                string $projectPublicDir,
                                Security $security,
                                Filesystem $filesystem)
    {
        parent::__construct($kernelProjectDir, $projectPublicDir, $security, $filesystem);

        $this->initiativeImagesPath = $initiativeImagesPath;
    }

    protected function path(): string
    {
        return $this->initiativeImagesPath;
    }
}