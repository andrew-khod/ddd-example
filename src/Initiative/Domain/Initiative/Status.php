<?php

namespace App\Initiative\Domain\Initiative;

interface Status
{
    public function __toString(): string;
    public function transaction(): string;
}