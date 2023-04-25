<?php

namespace App\Shared\Domain;

interface ActiveLanguage
{
    public function language(): ?string;
}