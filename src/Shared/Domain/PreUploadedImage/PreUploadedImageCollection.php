<?php

declare(strict_types=1);

namespace App\Shared\Domain\PreUploadedImage;

use App\Shared\Domain\BaseCollection;

class PreUploadedImageCollection extends BaseCollection
{
    public function __construct(PreUploadedImage ...$images)
    {
        parent::__construct(...$images);
    }

    public function get(int $int): ?PreUploadedImage
    {
        return $this->count() ? $this->toArray()[0] : null;
    }
}
