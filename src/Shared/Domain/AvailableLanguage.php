<?php

namespace App\Shared\Domain;

use Symfony\Component\Uid\Uuid;

//fixme remove Entity dependency?
class AvailableLanguage implements Entity
{
    private Uuid $id;

    private string $name;

    public function id(): BaseId
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->name();
    }

    public function name(): string
    {
        return $this->name;
    }

//    public function equals(Language $language): bool
//    public function equals($language): bool
//    {
//        return (string) $this === (string) $language;
//    }
}