<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\Query\FilterNormalizer;

use App\Initiative\Domain\Category\CategoryId;
use Doctrine\Common\Collections\Expr\Comparison;

class CategoryNormalizer implements FilterNormalizer
{
    private string $field;
    private array $value;

    public function __construct(string $field, array $value)
    {
        $this->field = $field;
        $this->value = $value;
    }

    public function field(): string
    {
        return sprintf('%s.id', $this->field);
    }

    public function value(): array
    {
        return array_map(
            fn (string $category) => (new CategoryId($category))->toBinary(),
            $this->value
        );
    }

    public function operator(): string
    {
        return Comparison::IN;
    }
}