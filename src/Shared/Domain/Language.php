<?php

namespace App\Shared\Domain;

use DateTime;
use Symfony\Component\Uid\Uuid;

//fixme remove Entity dependency?
//class Language implements Entity
class Language
{
    private Uuid $id;

    private string $name;

    private ?DateTime $deleted;

    public function __construct(Uuid $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

//    public function id(): BaseId
    public function id(): Uuid
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name();
    }

    // todo split on ActiveLanguage and DisabledLanguage to follow SRP
    public function isActive(): bool
    {
        return $this->deleted === null;
    }

    public function activate(): void
    {
        $this->deleted = null;
    }
}