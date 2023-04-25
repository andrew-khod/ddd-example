<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Image;

use App\Identity\Application\Security\Security;
use App\Shared\Infrastructure\S3ImageManager;
use Aws\S3\S3Client;

class ImageS3Manager extends S3ImageManager
{
    private string $initiativeImagesPath;

    public function __construct(string $kernelProjectDir,
                                string $projectPublicDir,
                                string $awsS3Bucket,
                                Security $security,
                                S3Client $filesystem,
                                string $initiativeImagesPath)
    {
        parent::__construct($kernelProjectDir,
            $projectPublicDir,
            $awsS3Bucket,
            $security,
            $filesystem);

        $this->initiativeImagesPath = $initiativeImagesPath;
    }

    protected function path(): string
    {
        return $this->initiativeImagesPath;
    }
}