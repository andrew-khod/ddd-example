<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure;

use App\Identity\Application\Security\Security;
use App\Shared\Application\ImageManager as ImageManagerInterface;
use App\Shared\Domain\PreUploadedImage\PreUploadedImage;
use App\Shared\Domain\PreUploadedImage\PreUploadedImageCollection;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class BaseImageManager implements ImageManagerInterface
{
    // TODO dont extend this class, reuse it by composition instead
    private Security $security;
    private string $projectPath;
    private string $projectPublicDir;
    private Filesystem $filesystem;

    public function __construct(string $kernelProjectDir,
                                string $projectPublicDir,
                                Security $security,
                                Filesystem $filesystem)
    {
        $this->security = $security;
        $this->projectPath = $kernelProjectDir;
        $this->projectPublicDir = $projectPublicDir;
        $this->filesystem = $filesystem;
    }

    abstract protected function path(): string;

    public function handle(PreUploadedImageCollection $images): PreUploadedImageCollection
    {
        return new PreUploadedImageCollection(...array_map(function (PreUploadedImage $image) {
            $file = new UploadedFile($image->pathname(), $image->name());
            $shortName = $this->security->passwordRecoveryToken()->token();
            $fullName = sprintf(
                '%s.%s',
                $shortName,
                $image->extension()
            );
            $path = $this->projectPath.$this->projectPublicDir.$this->path();
            $file = $file->move($path, $fullName);

            return new PreUploadedImage($image->id(), $path, $fullName, $file->getExtension());
        }, $images->toArray()));
    }

    public function remove(string $photo): void
    {
        $path = $this->projectPath
            .$this->projectPublicDir
            .$this->path()
            .DIRECTORY_SEPARATOR
            .$photo;
        $this->filesystem->remove([$path]);
    }
}
