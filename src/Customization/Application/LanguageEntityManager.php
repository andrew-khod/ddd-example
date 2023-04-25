<?php

namespace App\Customization\Application;

use App\Shared\Domain\Language;
use Symfony\Component\Uid\Uuid;

interface LanguageEntityManager
{
    public function create(Language $policy): void;
    public function delete(Language $language): void;
    public function persist(): void;
    public function nextId(): Uuid;
}