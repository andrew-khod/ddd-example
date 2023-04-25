<?php

declare(strict_types=1);

namespace App\Identity\Application\User\UseCase\UpdateUser;

use App\Identity\Application\Permission\Query\PermissionByCriteriaQuery;
use App\Identity\Application\Permission\Query\PermissionByIdCriteria;
use App\Identity\Application\Permission\Query\PermissionByNameCriteria;
use App\Identity\Application\User\AuthenticatedUser;
use App\Identity\Application\User\Query\UserByCriteriaQuery;
use App\Identity\Application\User\Query\UserByIdCriteria;
use App\Identity\Application\User\UserEntityManager;
use App\Identity\Domain\Permission\Permission;
use App\Identity\Domain\User\BaseUser;
use App\Identity\Domain\User\UserId;
use App\Identity\Domain\User\UserPermission;
use App\Shared\Application\ActiveTenant;

final class UpdateUserHandler
{
    private PermissionByCriteriaQuery $permissionByCriteriaQuery;
    private UserByCriteriaQuery $userByCriteriaQuery;
    private UserEntityManager $userEntityManager;
    private ActiveTenant $tenant;
    private AuthenticatedUser $authenticatedUser;

    public function __construct(PermissionByCriteriaQuery $permissionByCriteriaQuery,
                                UserByCriteriaQuery       $userByCriteriaQuery,
                                ActiveTenant $tenant,
                                AuthenticatedUser $authenticatedUser,
                                UserEntityManager         $userEntityManager,
    )
    {
        $this->permissionByCriteriaQuery = $permissionByCriteriaQuery;
        $this->userByCriteriaQuery = $userByCriteriaQuery;
        $this->userEntityManager = $userEntityManager;
        $this->tenant = $tenant;
        $this->authenticatedUser = $authenticatedUser;
    }

    public function handle(UpdateUserCommand $command): BaseUser
    {
        $currentUser = $this->authenticatedUser->user();
        $permissions = $command->permissions();
        $username = $command->username();
        $criteria = new PermissionByIdCriteria($permissions);
        $permissions = $this->permissionByCriteriaQuery->queryMultiple($criteria);

        $criteria = new UserByIdCriteria(new UserId($command->user()));
        $user = $this->userByCriteriaQuery->queryOne($criteria);
        //fixme extract string to PermissionDictionary
        $editAdminPermission = $this->permissionByCriteriaQuery->queryMultiple(
            new PermissionByNameCriteria(['admin:add'])
        )->toIDs()[0];
        $canEditAdmin = count(array_filter(
            $currentUser->permissionsByActiveCompany(),
            fn(Permission $p) => $p->id()->equals($editAdminPermission)
        )) && !$user->isSuperAdmin();
//        $canEditAdmin = count(array_filter($currentUser->permissionsByActiveCompany(), fn(UserPermission $p) => $p->id()->equals($editAdminPermission)));

        if ($user->username() !== $username
//            && ($currentUser->isSuperAdmin() || $currentUser->id()->equals($user->id()) || $currentUser->canEditAdmin())) {
            && ($currentUser->isSuperAdmin() || $currentUser->id()->equals($user->id()) || $canEditAdmin)) {
            $user->rename($username);
        }

        if ($currentUser->isSuperAdmin() || $canEditAdmin) {
            $user->changePermissions($this->tenant->company(), $permissions);
        }

        if ($currentUser->isSuperAdmin()) {
            if ($command->isSuperAdmin() && !$user->isSuperAdmin()) {
                $user->makeSuperAdmin();
            } else if (!$command->isSuperAdmin() && $user->isSuperAdmin()) {
                $user->makeSimpleAdmin();
            }
        }

        $this->userEntityManager->update();

        return $user;
    }
}
