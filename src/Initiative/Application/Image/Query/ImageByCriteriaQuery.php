<?php

namespace App\Initiative\Application\Image\Query;

use App\Initiative\Domain\Image\Image;
use App\Initiative\Domain\Image\ImageCollection;

interface ImageByCriteriaQuery
{
    public function queryOne(ImageCriteria $criteria): ?Image;

    public function queryMultiple(ImageCriteria $criteria): ImageCollection;
}
