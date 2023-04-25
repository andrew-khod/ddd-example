<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Identity\Application\Security\Security;
use App\Shared\Infrastructure\S3ImageManager;
use Aws\S3\S3Client;

class HeaderS3Manager extends S3ImageManager
{
    private string $headerImagePath;

    public function __construct(string $kernelProjectDir,
                                string $projectPublicDir,
                                string $awsS3Bucket,
                                Security $security,
                                S3Client $filesystem,
                                string $headerImagePath)
    {
        parent::__construct($kernelProjectDir,
            $projectPublicDir,
            $awsS3Bucket,
            $security,
            $filesystem);

        $this->headerImagePath = $headerImagePath;
    }

    protected function path(): string
    {
        return $this->headerImagePath;
    }
}