<?php

declare(strict_types=1);

namespace App\Identity\Application\User\UseCase\LinkGoogleAuth;

use App\Identity\Application\AuthVendor\AuthVendorEntityManager;
use App\Identity\Application\User\AuthenticatedUser;

final class LinkGoogleAuthHandler
{
    private GoogleAuth $googleAuth;

    private AuthenticatedUser $authenticatedUser;

    private AuthVendorEntityManager $authVendorEntityManager;

    public function __construct(GoogleAuth $googleAuth,
                                AuthenticatedUser $authenticatedUser,
                                AuthVendorEntityManager $authVendorEntityManager)
    {
        $this->googleAuth = $googleAuth;
        $this->authenticatedUser = $authenticatedUser;
        $this->authVendorEntityManager = $authVendorEntityManager;
    }

    public function handle(LinkGoogleAuthCommand $command): void
    {
        $code = $command->code();
        $oauthUser = $this->googleAuth->userByOneTimeCode($code);
        $user = $this->authenticatedUser->user();
        $authVendor = $oauthUser->toAuthVendor($user);

        $authVendor->linkWithUser($user);
        $this->authVendorEntityManager->save($authVendor);
    }
}
