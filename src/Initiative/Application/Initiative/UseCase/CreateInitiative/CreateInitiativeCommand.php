<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\UseCase\CreateInitiative;

use App\Initiative\Domain\Category\CategoryId;
use App\Shared\Domain\PreUploadedImage\PreUploadedImageCollection;

final class CreateInitiativeCommand
{
    private string $type;
    private string $category;
    private string $title;
    private string $description;
    private string $dateStart;
    private string $dateEnd;
    private ?int $minimalJoinedPeople;
    private ?string $location;
    private ?string $locationName;
    private ?float $locationRadiusValue;
    private ?string $locationRadiusUnit;
    private ?PreUploadedImageCollection $images;
    private array $questionnaires;

    public function __construct(string $type,
                                string $category,
                                string $title,
                                string $description,
                                string $dateStart,
                                string $dateEnd,
                                PreUploadedImageCollection $images = null,
                                int $minimalJoinedPeople = null,
                                string $location = null,
                                string $locationName = null,
                                float $locationRadiusValue = null,
                                string $locationRadiusUnit = null,
                                array $questionnaires = [])
    {
        $this->type = $type;
        $this->category = $category;
        $this->title = $title;
        $this->description = $description;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->minimalJoinedPeople = $minimalJoinedPeople;
        $this->location = $location;
        $this->locationName = $locationName;
        $this->locationRadiusValue = $locationRadiusValue;
        $this->locationRadiusUnit = $locationRadiusUnit;
        $this->images = $images;
        $this->questionnaires = $questionnaires;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function category(): CategoryId
    {
        return new CategoryId($this->category);
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function dateStart(): string
    {
        return $this->dateStart;
    }

    public function dateEnd(): string
    {
        return $this->dateEnd;
    }

    public function minimalJoinedPeople(): ?int
    {
        return $this->minimalJoinedPeople;
    }

    public function location(): ?string
    {
        return $this->location;
    }

    public function locationName(): ?string
    {
        return $this->locationName;
    }

    public function locationRadiusValue(): ?float
    {
        return $this->locationRadiusValue;
    }

    public function locationRadiusUnit(): ?string
    {
        return $this->locationRadiusUnit;
    }

    public function images(): ?PreUploadedImageCollection
    {
        return $this->images;
    }

    public function questionnaires(): array
    {
        //todo validate questionnaires structure by using QuestionnaireDTO or alike
        return $this->questionnaires;
    }
}
