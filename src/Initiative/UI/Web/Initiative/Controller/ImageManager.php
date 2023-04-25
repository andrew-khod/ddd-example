<?php

declare(strict_types=1);

namespace App\Initiative\UI\Web\Initiative\Controller;

use App\Initiative\Domain\Image\ImageId;
use App\Shared\Domain\PreUploadedImage\PreUploadedImage;
use App\Shared\Domain\PreUploadedImage\PreUploadedImageCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\UuidV4;

class ImageManager
{
    public function prepare(UploadedFile ...$images): PreUploadedImageCollection
    {
        return new PreUploadedImageCollection(...array_map(
            fn (UploadedFile $file) => new PreUploadedImage(
                new ImageId((new UuidV4())->toRfc4122()),
                $file->getPath(),
                $file->getBasename(),
                strlen($file->getClientOriginalExtension()) > 0
                    ? $file->getClientOriginalExtension()
                    : $file->guessClientExtension()
            ),
            $images
        ));
    }
}
