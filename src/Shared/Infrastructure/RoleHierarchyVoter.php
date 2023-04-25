<?php

namespace App\Shared\Infrastructure;

use App\Identity\Application\Permission\Query\PermissionByCriteriaQuery;
use App\Identity\Application\Permission\Query\PermissionByIdCriteria;
use App\Identity\Domain\Permission\Permission;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class RoleHierarchyVoter extends \Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter
{
    private PermissionByCriteriaQuery $permissionByCriteriaQuery;

    public function __construct(RoleHierarchyInterface    $roleHierarchy,
                                PermissionByCriteriaQuery $permissionByCriteriaQuery,
                                string                    $prefix = 'ROLE_')
    {
        parent::__construct($roleHierarchy, '');

        $this->permissionByCriteriaQuery = $permissionByCriteriaQuery;
    }

    protected function extractRoles(TokenInterface $token)
    {
        $permissions = parent::extractRoles($token);

        $criteria = new PermissionByIdCriteria($permissions);
        $permissions = $this->permissionByCriteriaQuery->queryMultiple($criteria);

        return array_map(fn(Permission $permission) => $permission->permission(), $permissions->toArray());
    }
}