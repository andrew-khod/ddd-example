<?php

declare(strict_types=1);

namespace App\Identity\Domain\User;

use App\Identity\Domain\Company\Company;
use App\Identity\Domain\Permission\Permission;
use App\Identity\Domain\Permission\PermissionId;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

class UserPermission
{
    private Permission $permission;
//    private PermissionId $permission_id;

    private BaseUser $user;

    private Uuid $id;

    private Company $company;

    public function __construct(BaseUser $user, Company $company, Permission $permission)
    {
        $this->id = new UuidV4();
//        $this->id = (new UuidV4())->toRfc4122();
//        $this->permission_id = $permission->id();
        $this->permission = $permission;
        $this->user = $user;
        $this->company = $company;
    }

//    public function id(): PermissionId
    public function id()
    {
        return $this->id;
//        return $this->permission_id;
    }

    public function company(): Company
    {
        return $this->company;
    }

    public function permission(): Permission
    {
        return $this->permission;
    }
}
