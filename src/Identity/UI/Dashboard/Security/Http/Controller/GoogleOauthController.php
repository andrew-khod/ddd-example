<?php

namespace App\Identity\UI\Dashboard\Security\Http\Controller;

use App\Identity\Application\User\Query\UserByGoogleAuthQuery;
use App\Identity\Application\User\UseCase\LinkGoogleAuth\LinkGoogleAuthCommand;
use App\Identity\Application\User\UseCase\LinkGoogleAuth\LinkGoogleAuthHandler;
use App\Identity\Domain\User\UserTranslation;
use App\Identity\UI\Dashboard\Security\Http\Request\LinkGoogleAuth\LinkGoogleAuthRequest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use OpenApi\Annotations\MediaType;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Schema;
use OpenApi\Annotations\Tag;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Tag(name="Identity")
 */
class GoogleOauthController extends AbstractFOSRestController
{
    /**
     * @Post(summary="Link Google auth provider to local account", description="Link Google auth provider to local account")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="code", type="string"),
     * )))
     */
    public function linkage(LinkGoogleAuthHandler $linkGoogleAuthHandler, LinkGoogleAuthRequest $request)
    {
        $code = $request->get('code');

        $command = new LinkGoogleAuthCommand($code);
        $linkGoogleAuthHandler->handle($command);

        $view = $this->view(UserTranslation::AUTH_VENDOR_ALLOWED, Response::HTTP_CREATED);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Sign in by Google", description="Sign in by Google")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="code", type="string"),
     * )))
     */
    public function verification(AuthenticationSuccessHandler $authenticationSuccessHandler,
                                 UserByGoogleAuthQuery $userByGoogleAuthQuery,
                                 LinkGoogleAuthRequest $request)
    {
        $code = $request->get('code');
        $user = $userByGoogleAuthQuery->query($code);

        return $authenticationSuccessHandler->handleAuthenticationSuccess($user);
    }
}
