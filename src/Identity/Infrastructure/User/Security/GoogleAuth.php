<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\User\Security;

use App\Identity\Application\User\UseCase\LinkGoogleAuth\GoogleAuth as GoogleAuthInterface;
use App\Identity\Application\User\UseCase\LinkGoogleAuth\GoogleUser;
use App\Identity\Application\User\UseCase\LinkGoogleAuth\InvalidOneTimeCodeException;
use League\OAuth2\Client\Grant\AuthorizationCode;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\Google;
use Symfony\Component\HttpFoundation\RequestStack;

final class GoogleAuth implements GoogleAuthInterface
{
    private Google $google;

    public function __construct(string $clientId,
                                string $clientSecret,
                                string $clientRedirectUri,
                                RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();
        $redirectUri = $request->headers->get('X-GAuth-Redirect-Uri');

        if ($redirectUri) {
            $clientRedirectUri = $redirectUri;
        }

        $this->google = new Google([
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => $clientRedirectUri,
        ]);
    }

    public function userByOneTimeCode(string $code): GoogleUser
    {
        try {
            $token = $this->google->getAccessToken(new AuthorizationCode(), [
                'code' => $code,
            ]);
        } catch (IdentityProviderException $exception) {
            throw new InvalidOneTimeCodeException();
        }

        $user = $this->google->getResourceOwner($token);

        return new GoogleUser(
            $user->getId(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getEmail(),
        );
    }
}
