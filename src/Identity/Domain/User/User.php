<?php

declare(strict_types=1);

namespace App\Identity\Domain\User;

use App\Identity\Domain\Company\Company;
use App\Identity\Domain\Company\CompanyCollection;
use App\Identity\Domain\Permission\PermissionCollection;
use App\Identity\Domain\Role\RoleCollection;
use App\Identity\Domain\UserCompany\UserCompany;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class User implements BaseUser
{
    private UserId $id;

    private Email $email;

    private Collection $roles;

    private Collection $permissions;

    private Collection $companies;

    private Password $password;

    private ?string $username = null;

    private RoleCollection $availableRoles;

    private DateTime $created;

    private DateTime $deleted;

    private Company $activeCompany;
//    private UserCompany $activeCompany;

    private bool $is_superadmin = false;
//    private CompanyCollection $allCompaniesList;
    private array $allCompaniesList = [];

    public function __construct(UserId $id,
                                Email $email,
                                Password $password,
                                PermissionCollection $permissions,
                                Company $activeCompany,
                                string $username = null,
                                array $roles = [])
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->roles = new ArrayCollection($roles);

        $userPermissions = [];

        foreach ($permissions->toArray() as $permission) {
            $userPermissions[] = new UserPermission($this, $activeCompany, $permission);
        }

        $this->permissions = new ArrayCollection($userPermissions);
        $this->username = $username;
        $this->companies = new ArrayCollection();
        // fixme oneToOne mapping User->UserCompany
        //        $this->activeCompany = new UserCompany($this, $activeCompany);
        $this->activeCompany = $activeCompany;
//        $this->allCompaniesList = new CompanyCollection();
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->email->value();
    }

//    TODO can't use even Collection return type because of Lexik\JWTUserToken require roles array; think about using adapter
//    public function getRoles(): Collection
    public function getRoles(): array
    {
        return $this->permissions()->toIDs(true);
//        return $this->permissions->map(
//            fn(UserPermission $permission) => (string) $permission->id()
//        )->toArray();
//
//        // FIXME find the way to map user roles by userId from external db to User::getRoles()
//        // FIXME f.i. move mapping "user->roleIds to proxyUser->roleNames" to whatever external user decorator/proxy
//        if ($this->availableRoles) {
//            $roles = [];
//
//            foreach ($this->availableRoles->toArray() as $availableRole) {
//                foreach ($this->roles->toArray() as $role) {
//                    if ($role->id()->id() === $availableRole->id()->id()) {
//                        $roles[] = $availableRole->role();
//                    }
//                }
//            }
//
//            return $roles;
//        }
//
//        return array_map(function (UserRole $role) {
//            return $role->id()->value();
//        }, $this->roles->toArray());
    }

    public function availableRoles(RoleCollection $roles): void
    {
        $this->availableRoles = $roles;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function changePassword(Password $password): void
    {
        $this->password = $password;
    }

    public function created(): DateTime
    {
        return $this->created;
    }

    public function changePermissions(Company $company, PermissionCollection $permissions): void
    {
        // todo check if user assigned to company

        $currentPermissions = $this->permissions->toArray();

        array_walk($currentPermissions, function (UserPermission $permission) use ($company) {
            if ($permission->company()->id()->equals($company->id())) {
                $this->permissions->removeElement($permission);
            }
        });

        foreach ($permissions->toArray() as $permission) {
            $this->permissions->add(new UserPermission($this, $company, $permission));
        }
    }

    public function permissions(Company $company = null): PermissionCollection
    {
        $permissions = $this->permissions;

        if ($company) {
            $permissions = $permissions->filter(
                    fn(UserPermission $permission) => $permission->company()->id()->equals($company->id())
                );
        }

        return new PermissionCollection(
            ...$permissions->map(fn(UserPermission $permission) => $permission->permission())->toArray()
        );
    }

    // todo expose domain Username
    public function username(): ?string
    {
        return $this->username;
    }

    public function rename(?string $username): void
    {
        $this->username = $username;
    }

    public function activeCompany(): Company
    {
        return $this->activeCompany;
//        return $this->activeCompany->company();
    }

//    public function activeUserCompany(): UserCompany
//    {
//        return $this->activeCompany;
//    }

    //todo return type CompanyCollection
    public function companies(): array
    {
        if ($this->isSuperAdmin()) {
            return $this->allCompaniesList;
        }

        return array_map(fn(UserCompany $company) => $company->company(), $this->companies->toArray());
    }

    public function switchCompany(Company $company): void
    {
        if ($this->isSuperAdmin() || $this->isAssignedToCompany($company)) {
            $this->activeCompany = $company;
        }
        // todo else throw exception
    }

    public function isSuperAdmin(): bool
    {
        return $this->is_superadmin;
    }

    public function makeSuperAdmin(): void
    {
        $this->is_superadmin = true;
    }

    public function makeSimpleAdmin(): void
    {
        $this->is_superadmin = false;
    }

    public function allCompanies(CompanyCollection $companies): void
    {
        $this->allCompaniesList = $companies->toArray();
    }

    public function isAssignedToCompany(Company $company): bool
    {
        return count(array_filter(
            $this->companies(),
                fn(Company $c) => $c->id()->equals($company->id()))
            ) > 0;
//        return $this->companies->contains($company);
    }

//    public function canEditAdmin(): bool
//    {
//        //todo extract permission string below to PermissionsDictionary
//        return $this->permissions->exists(
//            fn(int $i, UserPermission $permission) => $permission->permission() === 'admin:add'
//        );
//    }
    public function permissionsByActiveCompany(): array
    {
        return $this->permissions($this->activeCompany)->toArray();
    }
}
