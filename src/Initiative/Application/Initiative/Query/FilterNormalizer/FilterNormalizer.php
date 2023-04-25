<?php

namespace App\Initiative\Application\Initiative\Query\FilterNormalizer;

interface FilterNormalizer
{
//    public function field(): string;
    public function field();
    public function value(): mixed;
    public function operator(): string;
}