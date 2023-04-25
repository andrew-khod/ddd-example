<?php

declare(strict_types=1);

namespace App\Initiative\Application\Image\Query;

use App\Initiative\Domain\Image\ImageId;
use Doctrine\ORM\Query\Expr\Comparison;

final class ImageByIdCriteria implements ImageCriteria
{
    private array $images;

    public function __construct(ImageId ...$images)
    {
        // TODO using array of EntityId is impossible with findBy(), find the proper way
        $this->images = array_map(fn (ImageId $id) => $id->toBinary(), $images);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->images,
        ];
    }

    public function operatorFor(string $field): ?string
    {
        return Comparison::EQ;
    }
}
