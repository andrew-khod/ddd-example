<?php

namespace App\Identity\Domain\User;

// TODO try to abstract this Symfony imports
use App\Identity\Domain\Role\RoleCollection;
use App\Shared\Domain\BaseId;
use App\Shared\Domain\Entity;
use Symfony\Component\Security\Core\User\UserInterface;

interface BaseUser extends UserInterface, Entity
{
    public function id(): BaseId;

    public function email(): Email;

    public function changePassword(Password $password): void;

    public function availableRoles(RoleCollection $roles): void;
}
