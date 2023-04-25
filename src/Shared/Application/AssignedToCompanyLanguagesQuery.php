<?php

namespace App\Shared\Application;

interface AssignedToCompanyLanguagesQuery
{
    public function query(): ?array;
    public function includeDeleted(): void;
}