<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Image;

use App\Initiative\Domain\Initiative\Initiative;
use App\Shared\Domain\BaseId;
use App\Shared\Domain\Entity;

class Image implements Entity
{
    private ImageId $id;

    private string $pathname;

    private Initiative $initiative;

    public function __construct(ImageId $id,
                                Initiative $initiative,
                                string $pathname)
    {
        $this->pathname = $pathname;
        $this->initiative = $initiative;
        $this->id = $id;
    }

    public function pathname(): string
    {
        return $this->pathname;
    }

    public function id(): BaseId
    {
        return $this->id;
    }

    public function initiative(): Initiative
    {
        return $this->initiative;
    }
}
