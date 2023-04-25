<?php

namespace App\Identity\Application\User;

use App\Identity\Domain\Company\Company;
use App\Identity\Domain\User\User;
use App\Identity\Domain\User\UserId;

interface UserEntityManager
{
    public function create(User $user, Company $company): void;
//    public function create(BaseUser $user, Company $company): void;

    public function update(): void;

    public function nextId(): UserId;

    public function delete(User $user): void;

//    public function tenant(SwitchableActiveTenant $tenant);
}
