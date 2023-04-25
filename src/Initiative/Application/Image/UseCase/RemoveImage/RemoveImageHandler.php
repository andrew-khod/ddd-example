<?php

declare(strict_types=1);

namespace App\Initiative\Application\Image\UseCase\RemoveImage;

use App\Initiative\Application\Image\Query\ImageByCriteriaQuery;
use App\Initiative\Application\Image\Query\ImageByIdCriteria;
use App\Initiative\Application\Initiative\InitiativeEntityManager;
use App\Initiative\Domain\Image\ImageId;

final class RemoveImageHandler
{
    private InitiativeEntityManager $initiativeEntityManager;
    private ImageByCriteriaQuery $imageByCriteriaQuery;

    public function __construct(ImageByCriteriaQuery $imageByCriteriaQuery, InitiativeEntityManager $initiativeEntityManager)
    {
        $this->imageByCriteriaQuery = $imageByCriteriaQuery;
        $this->initiativeEntityManager = $initiativeEntityManager;
    }

    public function handle(RemoveImageCommand $command): void
    {
        $criteria = new ImageByIdCriteria(new ImageId($command->image()));
        $image = $this->imageByCriteriaQuery->queryOne($criteria);

        $image->initiative()->removeImage($image);

        $this->initiativeEntityManager->update();
    }
}
