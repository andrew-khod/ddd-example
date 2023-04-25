<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Image;

use App\Initiative\Application\Image\Query\ImageByCriteriaQuery as ImageByCriteriaQueryInterface;
use App\Initiative\Application\Image\Query\ImageCriteria;
use App\Initiative\Domain\Image\Image;
use App\Initiative\Domain\Image\ImageCollection;
use App\Shared\Infrastructure\Doctrine\Query\SwitchableTenantBaseQuery;

class ImageByCriteriaQuery extends SwitchableTenantBaseQuery implements ImageByCriteriaQueryInterface
{
    protected function getClass(): string
    {
        return Image::class;
    }

    public function queryOne(ImageCriteria $criteria): ?Image
    {
        return $this->findOneByCriteria($criteria);
    }

    public function queryMultiple(ImageCriteria $criteria): ImageCollection
    {
        return new ImageCollection(...$this->findMultipleByCriteria($criteria));
    }
}
