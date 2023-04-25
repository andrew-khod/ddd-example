<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\User\Persistence\Doctrine\Query;

use App\Identity\Application\AuthVendor\Query\AuthVendorByCriteriaQuery;
use App\Identity\Application\AuthVendor\Query\AuthVendorByVendorUserIdCriteria;
use App\Identity\Application\User\Exception\UserException;
use App\Identity\Application\User\Query\UserByCriteriaQuery;
use App\Identity\Application\User\Query\UserByGoogleAuthQuery as UserByGoogleAuthQueryInterface;
use App\Identity\Application\User\Query\UserByIdCriteria;
use App\Identity\Application\User\UseCase\LinkGoogleAuth\GoogleAuth;
use App\Identity\Domain\User\User;

final class UserByGoogleAuthQuery implements UserByGoogleAuthQueryInterface
{
    private GoogleAuth $googleAuth;

    private AuthVendorByCriteriaQuery $authVendorByCriteria;

    private UserByCriteriaQuery $userByCriteriaQuery;

    public function __construct(GoogleAuth $googleAuth,
                                UserByCriteriaQuery $userByCriteriaQuery,
                                AuthVendorByCriteriaQuery $authVendorByCriteria)
    {
        $this->googleAuth = $googleAuth;
        $this->authVendorByCriteria = $authVendorByCriteria;
        $this->userByCriteriaQuery = $userByCriteriaQuery;
    }

    public function query(string $oneTimeCode): ?User
    {
        $oauthUser = $this->googleAuth->userByOneTimeCode($oneTimeCode);

        $criteria = new AuthVendorByVendorUserIdCriteria($oauthUser->id());
        $authVendor = $this->authVendorByCriteria->queryOne($criteria);

        if (!$authVendor) {
            throw UserException::authVendorNotAllowed();
        }

        $criteria = new UserByIdCriteria($authVendor->userId());

        return $this->userByCriteriaQuery->queryOne($criteria);
    }
}
