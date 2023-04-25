<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure;

use App\Identity\Application\Security\Security;
use App\Shared\Application\ImageManager as ImageManagerInterface;
use App\Shared\Domain\PreUploadedImage\PreUploadedImage;
use App\Shared\Domain\PreUploadedImage\PreUploadedImageCollection;
use Aws\S3\S3Client;

abstract class S3ImageManager implements ImageManagerInterface
{
    // TODO dont extend this class, reuse it by composition instead
    private Security $security;
    private string $projectPath;
    private string $projectPublicDir;
    private S3Client $filesystem;
    private string $awsS3Bucket;

    public function __construct(string $kernelProjectDir,
                                string $projectPublicDir,
                                string $awsS3Bucket,
                                Security $security,
                                S3Client $filesystem)
    {
        $this->security = $security;
        $this->projectPath = $kernelProjectDir;
        $this->projectPublicDir = $projectPublicDir;
        $this->filesystem = $filesystem;
        $this->awsS3Bucket = $awsS3Bucket;
    }

    abstract protected function path(): string;

    public function handle(PreUploadedImageCollection $images): PreUploadedImageCollection
    {
        return new PreUploadedImageCollection(...array_map(function (PreUploadedImage $image) {
            $name = sprintf(
                '%s.%s',
                $this->security->passwordRecoveryToken()->token(),
                $image->extension()
            );

            $this->filesystem->putObject([
                'Bucket' => $this->awsS3Bucket,
                'Key' => sprintf('%s/%s', $this->path(), $name),
                'SourceFile' => $image->pathname(),
                'ACL' => 'public-read',
                'ContentType' => sprintf('image/%s', $image->extension()),
//                    // todo implement in abstract function S3ImageManager::contentType()
//                    $image->extension() === 'svg'
//                        ? 'image/svg+xml'
//                        : sprintf('image/%s', $image->extension()),
            ]);

            return new PreUploadedImage($image->id(), $this->path(), $name, $image->extension());
        }, $images->toArray()));
    }

    public function remove(string $image): void
    {
        $this->filesystem->deleteObject([
            'Bucket' => $this->awsS3Bucket,
            'Key' => $image,
        ]);
    }
}
