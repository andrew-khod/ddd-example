<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\Query\FilterNormalizer;

use Doctrine\Common\Collections\Expr\Comparison;

class DateStartNormalizer implements FilterNormalizer
{
    private string $value;
    private string $field;

    public function __construct(string $field, string $value)
    {
        $this->value = $value;
        $this->field = $field;
    }

    public function field(): string
    {
        return $this->field;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function operator(): string
    {
        return Comparison::GTE;
    }
}