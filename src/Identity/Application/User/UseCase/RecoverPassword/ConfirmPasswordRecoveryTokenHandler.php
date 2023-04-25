<?php

declare(strict_types=1);

namespace App\Identity\Application\User\UseCase\RecoverPassword;

use App\Identity\Application\Security\Security;
use App\Identity\Application\User\Exception\UserException;
use App\Identity\Application\User\PasswordRecoveryTokenEntityManager;
use App\Identity\Application\User\Query\PasswordRecoveryTokenQuery;
use App\Identity\Application\User\Query\UserByCriteriaQuery;
use App\Identity\Application\User\Query\UserByIdCriteria;
use App\Identity\Application\User\Query\UserByUserIdCriteria;
use App\Identity\Application\User\UserEntityManager;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\ActiveLanguage;

final class ConfirmPasswordRecoveryTokenHandler
{
    private UserByCriteriaQuery $userByCriteriaQuery;

    private UserEntityManager $userEntityManager;

    private MessageBus $messageBus;

    private PasswordRecoveryTokenQuery $passwordRecoveryTokenQuery;

    private PasswordRecoveryTokenEntityManager $passwordRecoveryTokenEntityManager;

    private Security $security;

//    private UserCompanyByCriteriaQuery $userCompanyByCriteriaQuery;

    private ActiveLanguage $activeLanguage;

    public function __construct(UserByCriteriaQuery $userByCriteriaQuery,
                                UserEntityManager $userEntityManager,
//                                UserCompanyByCriteriaQuery $userCompanyByCriteriaQuery,
                                Security $security,
                                PasswordRecoveryTokenQuery $passwordRecoveryTokenQuery,
                                PasswordRecoveryTokenEntityManager $passwordRecoveryTokenEntityManager,
                                ActiveLanguage $activeLanguage,
                                MessageBus $messageBus)
    {
        $this->userByCriteriaQuery = $userByCriteriaQuery;
        $this->userEntityManager = $userEntityManager;
        $this->messageBus = $messageBus;
        $this->passwordRecoveryTokenQuery = $passwordRecoveryTokenQuery;
        $this->passwordRecoveryTokenEntityManager = $passwordRecoveryTokenEntityManager;
        $this->security = $security;
//        $this->userCompanyByCriteriaQuery = $userCompanyByCriteriaQuery;
        $this->activeLanguage = $activeLanguage;
    }

    public function handle(ConfirmPasswordRecoveryTokenCommand $command): void
    {
        $token = $command->token();
        $token = $this->passwordRecoveryTokenQuery->byToken($token);

        if (!$token) {
            throw UserException::passwordRecoveryTokenNotExist();
        }

        $criteria = new UserByUserIdCriteria($token->userId());
//        $userCompany = $this->userCompanyByCriteriaQuery->queryOne($criteria);
//        $company = $userCompany->company();

        $criteria = new UserByIdCriteria($token->userId());
//        $userEntityManager = $this->userByCriteriaQuery->tenant($userCompany->company());
//        $this->userByCriteriaQuery->tenant($company);
        $user = $this->userByCriteriaQuery->queryOne($criteria);

        //todo try to changePassword for user directly bypassing security service
        $this->security->changeUserPassword($user, $command->password());
//        $this->userEntityManager->tenant($tenant);
//        $this->userEntityManager->update();
        $this->userEntityManager->update();
//        $userEntityManager->flush();

        $this->messageBus->dispatch(new UserPasswordChanged($user, $this->activeLanguage));

        $this->passwordRecoveryTokenEntityManager->delete($token);
    }
}
