<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Customer\Photo;

use App\Identity\Application\Security\Security;
use App\Shared\Infrastructure\S3ImageManager;
use Aws\S3\S3Client;

class PhotoS3Manager extends S3ImageManager
{
    private string $customerPhotoPath;

    public function __construct(string $kernelProjectDir,
                                string $projectPublicDir,
                                string $awsS3Bucket,
                                Security $security,
                                string $customerPhotoPath,
                                S3Client $filesystem)
    {
        parent::__construct($kernelProjectDir,
            $projectPublicDir,
            $awsS3Bucket,
            $security,
            $filesystem);

        $this->customerPhotoPath = $customerPhotoPath;
    }

    protected function path(): string
    {
        return $this->customerPhotoPath;
    }
}
