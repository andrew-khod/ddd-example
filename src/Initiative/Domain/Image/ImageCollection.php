<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Image;

use App\Shared\Domain\BaseCollection;

class ImageCollection extends BaseCollection
{
    public function __construct(Image ...$images)
    {
        parent::__construct(...$images);
    }
}
