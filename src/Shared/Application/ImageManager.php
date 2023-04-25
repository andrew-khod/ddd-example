<?php

namespace App\Shared\Application;

use App\Shared\Domain\PreUploadedImage\PreUploadedImageCollection;

interface ImageManager
{
    public function handle(PreUploadedImageCollection $images): PreUploadedImageCollection;

    public function remove(string $photo): void;
}
