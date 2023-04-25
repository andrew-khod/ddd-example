<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\UpdateBrandAndStyle;

final class UpdateBrandAndStyleCommand
{
    private ?string $name;
    private ?string $alias;
    private ?string $url;
    private ?string $color;
    private ?string $footer;

    public function __construct(?string $name = null,
                                ?string $alias = null,
                                ?string $url = null,
                                ?string $color = null,
                                ?string $footer = null)
    {
        $this->name = $name;
        $this->alias = $alias;
        $this->url = $url;
        $this->color = $color;
        $this->footer = $footer;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function alias(): ?string
    {
        return $this->alias;
    }

    public function url(): ?string
    {
        return $this->url;
    }

    public function color(): ?string
    {
        return $this->color;
    }

    public function footer(): ?string
    {
        return $this->footer;
    }
}
