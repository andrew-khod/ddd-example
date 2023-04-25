<?php

declare(strict_types=1);

namespace App\Identity\Domain\Company;

use App\Identity\Domain\User\BaseUser;
use App\Identity\Domain\User\User;
use App\Identity\Domain\UserCompany\UserCompany;
use App\Shared\Domain\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

// todo think about making $alias, $color, $url, $header, $logo as mandatory fields
class Company implements Entity
{
    private CompanyId $id;

    private Name $name;

    private Collection $userCompanies;

    private ?string $alias = null;

    private ?string $color = null;

    private ?string $url = null;

    private ?string $header = null;

    private ?string $logo = null;
    private ?string $logo_second = null;
    private ?string $footer = null;

    public function __construct(CompanyId $id, Name $name)
    {
        $this->users = new ArrayCollection();
        $this->id = $id;
        $this->name = $name;
//        $this->alias = $this->name()->alias();
        $name = strtolower((string) $name);
        $name = str_ireplace(' ', '_', $name);
        $this->alias = $name;
    }

    public function id(): CompanyId
    {
        return $this->id;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function alias(): ?string
    {
        return $this->alias;
    }

    public function aliasPrefixed(): ?string
    {
        return sprintf('%s_company', strtolower($this->alias));
    }

    public function url(): ?string
    {
        return $this->url;
    }

    public function color(): ?string
    {
        return $this->color ? sprintf('#%s', $this->color) : null;
    }

    public function changeAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    public function rename(Name $name): void
    {
        $this->name = $name;
    }

    public function addUser(User $user): void
    {
        $this->userCompanies->add(new UserCompany($user, $this));
    }

    public function isUserExist(BaseUser $user): bool
    {
        $filter = $this->userCompanies->filter(fn (UserCompany $userCompany) => $userCompany->userId()->equals($user->id()));

        return !$filter->isEmpty();
    }

    public function changeUrl(string $url): void
    {
        $this->url = $url;
    }

    public function changeColor(string $color): void
    {
        $this->color = $color;
    }

    public function changeHeader(string $header): void
    {
        $this->header = $header;
    }

    public function header(): ?string
    {
        return $this->header;
    }

    public function logo(): ?string
    {
        return $this->logo;
    }

    public function changeLogo(string $logo): void
    {
        $this->logo = $logo;
    }

    public function secondLogo(): ?string
    {
        return $this->logo_second;
    }

    public function changeSecondLogo(string $logo): void
    {
        $this->logo_second = $logo;
    }

    public function footer(): ?string
    {
        return $this->footer;
    }

    public function changeFooter(string $footer): ?string
    {
        return $this->footer = $footer;
    }
}
