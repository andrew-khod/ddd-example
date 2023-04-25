<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\Query\FilterNormalizer;

use App\Initiative\Application\Initiative\InitiativeException;
use Doctrine\ORM\Query\Expr\Comparison;

class FilterToCriteriaNormalizerFactory
{
    public static function create(string $field, mixed $value): FilterNormalizer
    {
        return match ($field) {
            'categories' => new CategoryNormalizer($field, $value),
            'date_start' => new DateStartNormalizer($field, $value),
            'date_end' => new DateEndNormalizer($field, $value),
            'text' => new TextNormalizer($field, $value),
            default => throw InitiativeException::invalidFilterField($field),
        };
    }

    public function operatorFor(string $field): ?string
    {
        return Comparison::EQ;
    }
}
