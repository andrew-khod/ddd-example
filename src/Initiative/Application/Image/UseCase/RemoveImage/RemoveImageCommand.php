<?php

declare(strict_types=1);

namespace App\Initiative\Application\Image\UseCase\RemoveImage;

final class RemoveImageCommand
{
    private string $image;

    public function __construct(string $image)
    {
        $this->image = $image;
    }

    public function image(): string
    {
        return $this->image;
    }
}
