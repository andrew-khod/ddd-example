<?php

declare(strict_types=1);

namespace App\Identity\Application\User\UseCase\CreateUser;

use App\Identity\Application\Company\CompanyEntityManager;
use App\Identity\Application\Company\CompanyException;
use App\Identity\Application\Permission\Query\PermissionByCriteriaQuery;
use App\Identity\Application\Permission\Query\PermissionByIdCriteria;
use App\Identity\Application\Security\Security;
use App\Identity\Application\User\PasswordRecoveryTokenEntityManager;
use App\Identity\Application\User\Query\UserByCriteriaQuery;
use App\Identity\Application\User\Query\UserByEmailCriteria;
use App\Identity\Application\User\UserEntityManager;
use App\Identity\Domain\PasswordRecoveryToken\PasswordRecoveryToken;
use App\Identity\Domain\User\BaseUser;
use App\Identity\Domain\User\Email;
use App\Identity\Domain\User\Password;
use App\Identity\Domain\User\User;
use App\Shared\Application\ActiveTenant;
use App\Shared\Application\BaseActiveTenant;
use App\Shared\Application\MessageBus;

final class CreateUserHandler
{
    private PermissionByCriteriaQuery $permissionByCriteriaQuery;
    private UserEntityManager $userEntityManager;
    private Security $security;
//    private AuthenticatedUser $authenticatedUser;
//    private UserCompanyByUserQuery $companyByUserQuery;
    private MessageBus $messageBus;
    private PasswordRecoveryTokenEntityManager $passwordRecoveryTokenManager;
    private BaseActiveTenant $tenant;
    private UserByCriteriaQuery $userByCriteriaQuery;
    private CompanyEntityManager $companyEntityManager;

    public function __construct(PermissionByCriteriaQuery          $permissionByCriteriaQuery,
                                UserEntityManager                  $userEntityManager,
                                ActiveTenant $tenant,
                                UserByCriteriaQuery $userByCriteriaQuery,
                                CompanyEntityManager $companyEntityManager,
//                                BaseActiveTenant $tenant,
//                                AuthenticatedUser                  $authenticatedUser,
//                                UserCompanyByUserQuery             $companyByUserQuery,
                                MessageBus                         $messageBus,
                                Security                           $security,
                                PasswordRecoveryTokenEntityManager $passwordRecoveryTokenManager)
    {
        $this->permissionByCriteriaQuery = $permissionByCriteriaQuery;
        $this->userEntityManager = $userEntityManager;
        $this->security = $security;
//        $this->authenticatedUser = $authenticatedUser;
//        $this->companyByUserQuery = $companyByUserQuery;
        $this->messageBus = $messageBus;
        $this->passwordRecoveryTokenManager = $passwordRecoveryTokenManager;
        $this->tenant = $tenant;
        $this->userByCriteriaQuery = $userByCriteriaQuery;
        $this->companyEntityManager = $companyEntityManager;
    }

    public function handle(CreateUserCommand $command): BaseUser
    {
        $email = new Email($command->email());
        $username = $command->username();
        $permissions = $command->permissions();
        $nextUserId = $this->userEntityManager->nextId();
        $password = $this->security->randomPassword();
//        $company = $this->companyByUserQuery->query($this->authenticatedUser->user());
        $company = $this->tenant->company();

        if (!$company) {
            throw CompanyException::appKeyEmpty();
        }

        $criteria = new PermissionByIdCriteria($permissions);
        $permissions = $this->permissionByCriteriaQuery->queryMultiple($criteria);

        $user = $this->userByCriteriaQuery->queryOne(new UserByEmailCriteria($command->email()));

        if ($user) {
            $company->addUser($user);
            $user->changePermissions($company, $permissions);

            if ($command->isSuperAdmin()) {
                $user->makeSuperAdmin();
            }

            $this->companyEntityManager->update();

            return $user;
        }

        $user = new User(
            $nextUserId,
            $email,
            new Password($password->hash()),
            $permissions,
            $company,
            $username,
        );

        if ($command->isSuperAdmin()) {
            $user->makeSuperAdmin();
        }

        $this->userEntityManager->create($user, $company);

        $passwordRecoveryToken = $this->security->passwordRecoveryToken();
        $passwordRecoveryToken = new PasswordRecoveryToken($user->id(), $passwordRecoveryToken->token());
        $this->passwordRecoveryTokenManager->save($passwordRecoveryToken);

        $this->messageBus->dispatch(new UserCreated($user, $passwordRecoveryToken, $company));

        return $user;
    }
}
