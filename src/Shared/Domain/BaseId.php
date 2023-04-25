<?php

declare(strict_types=1);

namespace App\Shared\Domain;

// TODO get rid from vendor somehow, but doctrine findBy doesn't support auto param convert currently
use Symfony\Component\Uid\UuidV4;

abstract class BaseId
{
    private string $id;

    private UuidV4 $manager;

    public function __construct(string $id)
    {
        $this->id = $id;
        $this->manager = new UuidV4($id);
    }

    public function value(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function toBinary(): string
    {
        return $this->manager->toBinary();
    }

    public function equals(BaseId $id): bool
    {
        return $this->id === $id->value();
    }
}
