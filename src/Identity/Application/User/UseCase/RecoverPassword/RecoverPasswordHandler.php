<?php

declare(strict_types=1);

namespace App\Identity\Application\User\UseCase\RecoverPassword;

use App\Identity\Application\Security\Security;
use App\Identity\Application\User\Exception\UserException;
use App\Identity\Application\User\PasswordRecoveryTokenEntityManager;
use App\Identity\Application\User\Query\PasswordRecoveryTokenQuery;
use App\Identity\Application\User\Query\UserByCriteriaQuery;
use App\Identity\Application\User\Query\UserByEmailCriteria;
use App\Identity\Application\User\Query\UserByIdCriteria;
use App\Identity\Application\UserCompany\Query\UserCompanyByCriteriaQuery;
use App\Identity\Domain\PasswordRecoveryToken\PasswordRecoveryToken;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\ActiveLanguage;

final class RecoverPasswordHandler
{
    private UserByCriteriaQuery $userByCriteriaQuery;

    private Security $security;

    private MessageBus $messageBus;

    private PasswordRecoveryTokenEntityManager $passwordRecoveryTokenManager;

    private PasswordRecoveryTokenQuery $passwordRecoveryTokenQuery;

    private UserCompanyByCriteriaQuery $userCompanyByCriteriaQuery;

    private ActiveLanguage $activeLanguage;

    public function __construct(UserByCriteriaQuery $userByCriteriaQuery,
                                UserCompanyByCriteriaQuery $userCompanyByCriteriaQuery,
                                PasswordRecoveryTokenQuery $passwordRecoveryTokenQuery,
                                PasswordRecoveryTokenEntityManager $passwordRecoveryTokenManager,
                                Security $security,
                                ActiveLanguage $activeLanguage,
                                MessageBus $messageBus)
    {
        $this->userByCriteriaQuery = $userByCriteriaQuery;
        $this->security = $security;
        $this->messageBus = $messageBus;
        $this->passwordRecoveryTokenManager = $passwordRecoveryTokenManager;
        $this->passwordRecoveryTokenQuery = $passwordRecoveryTokenQuery;
        $this->userCompanyByCriteriaQuery = $userCompanyByCriteriaQuery;
        $this->activeLanguage = $activeLanguage;
    }

    public function handle(RecoverPasswordCommand $command): void
    {
        $email = $command->email();

        $criteria = new UserByEmailCriteria($email);
        $userCompany = $this->userCompanyByCriteriaQuery->queryOne($criteria);
//        $user = $this->userByCriteriaQuery->queryOne($criteria);

        if (!$userCompany) {
            throw UserException::userNotExist();
        }

        $passwordRecoveryToken = $this->passwordRecoveryTokenQuery->byUserId($userCompany->userId());
        $criteria = new UserByIdCriteria($userCompany->userId());
//        $criteria = new UserByStringIdCriteria($userCompany->userId());
//        $this->userByCriteriaQuery->tenant($userCompany->company());
        $user = $this->userByCriteriaQuery->queryOne($criteria);

        if ($passwordRecoveryToken) {
            $event = new PasswordRecoveryTokenGenerated($user, $passwordRecoveryToken);
            $this->messageBus->dispatch($event);

            return;
        }

        $passwordRecoveryToken = $this->security->passwordRecoveryToken();
        $passwordRecoveryToken = new PasswordRecoveryToken($user->id(), $passwordRecoveryToken->token());
        $event = new PasswordRecoveryTokenGenerated($user, $passwordRecoveryToken, $this->activeLanguage);

        $this->passwordRecoveryTokenManager->save($passwordRecoveryToken);
        $this->messageBus->dispatch($event);
    }
}
