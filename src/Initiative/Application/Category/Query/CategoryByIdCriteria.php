<?php

declare(strict_types=1);

namespace App\Initiative\Application\Category\Query;

use App\Initiative\Domain\Category\CategoryId;
use Doctrine\Common\Collections\Expr\Comparison;

final class CategoryByIdCriteria implements CategoryCriteria
{
    private array $categories;

    public function __construct(CategoryId ...$categories)
    {
        // TODO using array of EntityId is impossible with findBy(), find the proper way
        $this->categories = array_map(fn (CategoryId $id) => $id->toBinary(), $categories);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->categories,
        ];
    }

    public function operatorFor(string $field): ?string
    {
        return Comparison::EQ;
    }
}
