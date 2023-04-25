<?php

declare(strict_types=1);

namespace App\Shared\Domain\PreUploadedImage;

use App\Shared\Domain\BaseId;

class PreUploadedImage
{
    const ALLOWED = [
        'jpg',
        'jpeg',
        'png',
        'svg',
    ];
    private string $extension;
    private string $path;
    private string $name;
    private BaseId $id;

    public function __construct(BaseId $id,
                                string $path,
                                string $name,
                                string $extension)
    {
        if (!in_array($extension, self::ALLOWED)) {
            throw new NotValidImageException();
        }

        $this->id = $id;
        $this->extension = $extension;
        $this->path = $path;
        $this->name = $name;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function pathname(): string
    {
        return $this->path.DIRECTORY_SEPARATOR.$this->name;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function extension(): string
    {
        return $this->extension;
    }

    public function id(): BaseId
    {
        return $this->id;
    }
}
