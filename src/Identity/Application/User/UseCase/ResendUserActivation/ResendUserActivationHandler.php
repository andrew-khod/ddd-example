<?php

declare(strict_types=1);

namespace App\Identity\Application\User\UseCase\ResendUserActivation;

use App\Identity\Application\Security\Security;
use App\Identity\Application\User\Exception\UserException;
use App\Identity\Application\User\PasswordRecoveryTokenEntityManager;
use App\Identity\Application\User\Query\PasswordRecoveryTokenQuery;
use App\Identity\Application\User\Query\UserByCriteriaQuery;
use App\Identity\Application\User\Query\UserByIdCriteria;
use App\Identity\Application\User\UseCase\CreateUser\UserCreated;
use App\Identity\Domain\PasswordRecoveryToken\PasswordRecoveryToken;
use App\Identity\Domain\User\UserId;
use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use App\Shared\Application\MessageBus;

final class ResendUserActivationHandler
{
    private Security $security;
    private PasswordRecoveryTokenEntityManager $passwordRecoveryTokenManager;
    private MessageBus $messageBus;
    private UserByCriteriaQuery $userByCriteriaQuery;
    private SwitchableActiveTenant $tenant;
    private PasswordRecoveryTokenQuery $passwordRecoveryTokenQuery;

    public function __construct(Security                           $security,
                                UserByCriteriaQuery $userByCriteriaQuery,
                                PasswordRecoveryTokenEntityManager $passwordRecoveryTokenManager,
                                SwitchableActiveTenant $tenant,
                                PasswordRecoveryTokenQuery $passwordRecoveryTokenQuery,
                                MessageBus                         $messageBus)
    {
        $this->security = $security;
        $this->passwordRecoveryTokenManager = $passwordRecoveryTokenManager;
        $this->messageBus = $messageBus;
        $this->userByCriteriaQuery = $userByCriteriaQuery;
        $this->tenant = $tenant;
        $this->passwordRecoveryTokenQuery = $passwordRecoveryTokenQuery;
    }

    public function handle(ResendUserActivationCommand $command): void
    {
        $user = $this->userByCriteriaQuery->queryOneV2(new UserByIdCriteria(new UserId($command->user())));

        if (!$user) {
            throw UserException::userNotExist();
        }

        $passwordRecoveryToken = $this->passwordRecoveryTokenQuery->byUser($user);

        if (!$passwordRecoveryToken) {
            $passwordRecoveryToken = $this->security->passwordRecoveryToken();
            $passwordRecoveryToken = new PasswordRecoveryToken($user->id(), $passwordRecoveryToken->token());
            $this->passwordRecoveryTokenManager->save($passwordRecoveryToken);
        }

        $this->messageBus->dispatch(new UserCreated($user, $passwordRecoveryToken, $this->tenant->company()));
    }
}
