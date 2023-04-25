<?php

declare(strict_types=1);

namespace App\Identity\Application\User\UseCase\DeleteUser;

use App\Identity\Application\Permission\Query\PermissionByCriteriaQuery;
use App\Identity\Application\Permission\Query\PermissionByNameCriteria;
use App\Identity\Application\User\AuthenticatedUser;
use App\Identity\Application\User\Query\UserByCriteriaQuery;
use App\Identity\Application\User\Query\UserByIdCriteria;
use App\Identity\Application\User\UserEntityManager;
use App\Identity\Domain\Permission\Permission;
use App\Identity\Domain\User\UserId;
use App\Identity\Domain\User\UserPermission;

final class DeleteUserHandler
{
    private UserByCriteriaQuery $userByCriteriaQuery;
    private UserEntityManager $userEntityManager;
    private AuthenticatedUser $authenticatedUser;
    private PermissionByCriteriaQuery $permissionByCriteriaQuery;

    public function __construct(AuthenticatedUser   $authenticatedUser,
                                UserByCriteriaQuery $userByCriteriaQuery,
                                UserEntityManager   $userEntityManager,
                                PermissionByCriteriaQuery $permissionByCriteriaQuery)
    {
        $this->userByCriteriaQuery = $userByCriteriaQuery;
        $this->userEntityManager = $userEntityManager;
        $this->authenticatedUser = $authenticatedUser;
        $this->permissionByCriteriaQuery = $permissionByCriteriaQuery;
    }

    public function handle(DeleteUserCommand $command): void
    {
        $criteria = new UserByIdCriteria(new UserId($command->user()));
        $user = $this->userByCriteriaQuery->queryOne($criteria);
        $currentUser = $this->authenticatedUser->user();
        //fixme extract string to PermissionDictionary and think about moving to $currentUser->canEdit() or in separate service
        $editAdminPermission = $this->permissionByCriteriaQuery->queryMultiple(
            new PermissionByNameCriteria(['admin:add'])
        )->toIDs()[0];
        $canEditAdmin = count(array_filter(
            $currentUser->permissionsByActiveCompany(),
            fn(Permission $p) => $p->id()->equals($editAdminPermission)
        )) && !$user->isSuperAdmin();

//        if (!$currentUser->isSuperAdmin() || $currentUser->canEdit()) {
        if ($currentUser->isSuperAdmin() || $canEditAdmin) {
            $this->userEntityManager->delete($user);
            $this->userEntityManager->update();
        }
    }
}
